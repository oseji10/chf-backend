<?php

namespace App\Http\Controllers\API\CHFAdmin;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Patient;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class RecommendationController extends Controller
{
    protected Array $constraints = [];
    protected $users;
    protected $pool_fund = 3000000;

    public function index(){
        try {
            $this->authorize('viewAny',User::class);
            
            /* 
            *   TO GET PATIENTS THAT ARE YET TO BE APPROVED, GET ALL USERS WITH PATIENT RECORD
            *   PATIENTS WHERE YEARLY INCOME IS SET HAS COMPLETED THE ONBOARDING
            *   PATIENTS WHERE APPLICATION REVIEW DOES NOT EXIST HAS NOT BEEN APPROVED OR  
            *   DECLINED.
            */
            
            $users = Patient::whereNotNull('yearly_income')->whereNotNull('ailment_id')->doesntHave('applicationReview')->with('user')->with('ailment')->with('coe')->get();
            $this->pool_fund = SiteSetting::where('key','pool_account_balance')->first()->value;
            
            $this->users = $users;
            $config = $this->calculatePoints();
            $new_arr = [];

            foreach ( $this->users->sortByDesc('point')->take(50) as $recommendation) {
                $recommendation->recommended_fund = ($this->pool_fund / $this->constraints['total_points'] * $recommendation->point);
                array_push($new_arr, $recommendation);
            }
            
            return ['data' => $new_arr, $this->constraints];
        }catch(AuthorizationException $ex){
            return ResponseHelper::noDataErrorResponse('You are not authorized to perform this operation.', 403);
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse('Something went wrong',500);
        } 
    }
    
    /* 
    *   SUM THE TOTAL AGE AND TOTAL INCOME FOR ALL PENDING APPLICATANTS
    *   THIS WILL BE USED AS THE BASIS FOR RECOMMENDATION
    *   ALL POINTS ARE CALCULATED BASED ON CURRENT APPLICANTS IN POOL
     */
    protected function calculatePoints(){
        // $points = [
        //     'total_age' => 0,
        //     'total_yearly_income' => 0
        // ];

        $this->constraints['total_age'] = 0;
        $this->constraints['max_age'] = 0;
        $this->constraints['total_yearly_income'] = 0;
        $this->constraints['max_yearly_income'] = 0;
        $this->constraints['total_ailment_stage'] = 0;
        $this->constraints['max_ailment_stage'] = 0;
        $this->constraints['total_points'] = 0;

        $age_variance = 0;
        $income_variance = 0;

        foreach ($this->users as $user) {

            $this->constraints['total_age'] += $user->user->age(); 
            $this->constraints['total_yearly_income'] += $user->yearly_income; 
            $this->constraints['total_ailment_stage'] += $user->ailment->ailment_stage;

            if ($this->constraints['max_ailment_stage'] < $user->ailment->ailment_stage) {
                $this->constraints['max_ailment_stage'] = $user->ailment->ailment_stage;
            }

            if ($this->constraints['max_age'] < $user->user->age()) {
                $this->constraints['max_age'] = $user->user->age();
            }

            if ($this->constraints['max_yearly_income'] < $user->yearly_income) {
                $this->constraints['max_yearly_income'] = $user->yearly_income;
            }
        }

        foreach ($this->users as $user) {
            $age_variance += pow($user->user->age() - $this->constraints['total_age']/$this->users->count(),2);
            $income_variance += pow($user->yearly_income - $this->constraints['total_yearly_income']/$this->users->count(),2);
        }

        $this->constraints['age_standard_deviation'] = (float) sqrt($age_variance / $this->users->count());
        $this->constraints['income_standard_deviation'] = (float) sqrt($income_variance / $this->users->count());

        $this->calculateUserPoints();
        return $this;
    }

    protected function calculateUserPoints(){
        foreach ($this->users as $user) {
            $user->age_points = $this->calculateAgePoint($user);
            $user->ailment_point = $this->calculateAilmentPoint($user);
            $user->income_point = $this->calculateIncomePoint($user);
            $user->point = ($this->calculateAgePoint($user) + $this->calculateIncomePoint($user) + $this->calculateAilmentPoint($user));
            $this->constraints['total_points'] += $user->point;
        }
    }

    protected function calculateAilmentPoint($user){
        return round(1/$user->ailment->ailment_stage,5);
    }
    
    protected function calculateAgePoint($user){
    if ($user->user->age() < 13 || $user->user->age() > 80) {
        return 0.0125;
    };

    /*
    *   CALCULATE INVERSE PROPORTION OF AGE 
    *   AGE POINT 
    *
     */

    //  return round(1/($user->age() - 12),5);
        return round(($this->constraints['max_age']/$user->user->age())/$this->constraints['age_standard_deviation'],5);
    }

    protected function calculateIncomePoint($user){
        return round(($this->constraints['max_yearly_income']/$user->yearly_income) / $this->constraints['income_standard_deviation'], 4);
    }
}
