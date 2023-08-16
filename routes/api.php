<?php

use App\Helpers\AWSHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CHFAdmin\ApplicationController;
use App\Http\Controllers\API\CHFAdmin\FundController;
use App\Http\Controllers\API\User\EnrollmentController;
use App\Http\Controllers\API\User\ProfileController;
use App\Http\Controllers\API\User\UserVerificationController;
use App\Http\Controllers\API\User\PasswordController;
use App\Http\Controllers\API\CHFAdmin\PatientController;
use App\Http\Controllers\API\CHFAdmin\RecommendationController;
use App\Http\Controllers\API\COEAdmin\COEAdminPatientController;
use App\Http\Controllers\API\COEAdmin\ManageStaffController;
use App\Http\Controllers\API\Resource\CHFStaffController as ResourceCHFStaffController;
use App\Http\Controllers\API\COEStaff\BillingController;
use App\Http\Controllers\API\COEStaff\PatientController as COEStaffPatientController;
use App\Http\Controllers\API\COEStaff\ServiceController;
use App\Http\Controllers\API\FandA\PaymentController;
use App\Http\Controllers\API\FileControler;
use App\Http\Controllers\API\MDT\MDTCommentController;
use App\Http\Controllers\API\Patient\PatientBillingController;
use App\Http\Controllers\API\Superadmin\CHFAdminController;
use App\Http\Controllers\API\Superadmin\COEHelpDeskController;
use App\Http\Controllers\API\Resource\AilmentController;
use App\Http\Controllers\API\Resource\AnalyticController;
use App\Http\Controllers\API\Resource\ResourceController;
use App\Http\Controllers\API\Resource\ApplicationReviewController;
use App\Http\Controllers\API\Resource\PatientController as ResourcePatientController;
use App\Http\Controllers\API\Resource\COEController as ResourceCOEController;
use App\Http\Controllers\API\Resource\PermissionController;
use App\Http\Controllers\API\Resource\RoleController;
use App\Http\Controllers\API\Resource\ServiceController as ResourceServiceController;
use App\Http\Controllers\API\Resource\COEBillingController;
use App\Http\Controllers\API\Resource\GeopoliticalZoneController;
use App\Http\Controllers\API\Resource\IdentificationDocumentController;
use App\Http\Controllers\API\Resource\LGAController;
use App\Http\Controllers\API\Resource\ServiceCategoryController;
use App\Http\Controllers\API\Resource\StateController;
use App\Http\Controllers\API\Resource\UserController;
use App\Http\Controllers\API\Resource\UIMenuController;

use App\Http\Controllers\API\Resource\COEStaffController as ResourceCOEStaffController;
use App\Http\Controllers\API\Resource\PoolController;
use App\Http\Controllers\API\Resource\SiteSettingController;
use App\Http\Controllers\API\Superadmin\SplitController;
use App\Http\Controllers\API\Superadmin\TransactionController;
use App\Http\Controllers\API\Resource\Notification;
use App\Http\Controllers\API\Resource\BillingSummaryReportController;
use App\Http\Controllers\API\Resource\FundRetrievalController;
use App\Http\Controllers\API\Resource\PatientCommentController;
use App\Http\Controllers\API\Resource\TransactionDisputeController;
use App\Http\Controllers\API\TokenController;
// Patient Application Routes
use App\Http\Controllers\API\User\Patient\FamilyHistoryController;
use App\Http\Controllers\API\User\Patient\NextOfKinController;
use App\Http\Controllers\API\User\Patient\PersonalHistoryController;
use App\Http\Controllers\API\User\Patient\PersonalInformationController;
use App\Http\Controllers\API\User\Patient\SupportAssessmentController;
use App\Http\Controllers\API\User\Patient\SocialConditionController;
use App\Http\Controllers\API\User\Patient\SocialWorkerAssessmentController;

//Patient Appointment schedule
use App\Http\Controllers\API\Resource\PatientAppointmentController;
use App\Http\Controllers\API\Superadmin\ReportController;
use App\Mail\TestMail;
use App\Models\User;
use App\Http\Controllers\V2API\Resources\WalletTopupController;
use App\Http\Controllers\CustomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| *********** IMPORTANT! IMPORTANT!! IMPORTANT!!!
|--------------------------------------------------------------------------
|
| PLEASE DO NOT REORDER ROUTES! I REPEAT, PLEASE DO NOT REORDER ROUTES!!
|
| ROUTES IN THIS FILE FOLLOW THE RESTFUL PRINCIPLE
| SOME ROUTES WHICH ARE NOT RESOURCES ARE ALSO INCLUDED AND SHOULD BE ON TOP
|
|
*/


/*
*   PROTECT ALL ROUTES WITH MACHINE TO MACHINE CLIENT TOKEN
*/

Route::group(['middleware' => [/* PUT MIDDLEWARES HERE */]], function () {

    Route::get('/hash-password', function (Request $request) {
        return \Hash::make(request()->password);
    });

    Route::post('/test', function (Request $request) {
        return \Mail::to([
            'geefive3@gmail.com',
            'chinedu_ukpe@outlook.com',
            'chineduukpe@gmail.com',
            'ukpefriday99@gmail.com',
            'slidsolutions@outlook.com',
            'slidnigeria@outlook.com',
            'cfukpe@gmail.com',
            'cukpe@emgeresources.com'
        ])->send(new TestMail);
    });
    // Route::get('/mail-all-patients',function(){
    // $users = User::whereHas('applicationReview',function($query){
    //     $query->where('status','pending');
    // })->get();

    // foreach($users as $user){
    //     AWSHelper::sendSMS($user->phone_number,
    //     trim('URGENT: Your application is at the next stage. Visit the social welfare department of your hospital for review. Have your unique ID and this SMS when you visit'));
    // }
    // return "SMS Sent";
    // });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/coes', [ResourceCOEController::class, 'index']);
    Route::get('/coes/{coe_id}', [ResourceCOEController::class, 'view']);
    Route::get('/coes/{coe_id}/disputes', [TransactionDisputeController::class, 'getCOEDispute']);
    Route::get('/coes/{coe_id}/staff', [ResourceCOEController::class, 'getStaff']);
    Route::get('/coes/search/{search_value}', [ResourceCOEController::class, 'search']);
    Route::post('/password/recovery/send_code', [UserVerificationController::class, 'sendPasswordRecoveryEmail']);
    Route::post('/resend_email', [UserVerificationController::class, 'resendEmail']);
    Route::post('/verify_email', [UserVerificationController::class, 'verifyAccountEmail']);
    Route::post('/password/recovery/verify_code', [UserVerificationController::class, 'verifyRecoveryCode']);
    Route::put('/reset_password', [PasswordController::class, 'resetPassword']);
    Route::get('/identification_documents', [IdentificationDocumentController::class, 'index']);
    Route::post('/secure/superadmin/support/anonymous/auth', [AuthController::class, 'superLogin']);
    /*
    *   PROTECT THESE ROUTES USING PERSONAL ACCESS TOKENS. USER MUST BE LOGGED IN
     */
    Route::group(['middleware' => ['auth:api']], function () {

        // SHARED ROUTES
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::put('/profile', [ProfileController::class, 'updateProfile']);
        Route::get('/refresh_login', [AuthController::class, 'refreshLogin']);
        Route::put('/change_password', [PasswordController::class, 'changePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/documents', [FileControler::class, 'upload']);

        Route::get('/patients/{patient_id}/comments/{comment_id}', [PatientCommentController::class, 'view']);
        // APPLICATION REVIEW ROUTES
        Route::get('/application/reviews', [ApplicationReviewController::class, 'index']);
        Route::get('/application/reviews/{patient_id}', [ApplicationReviewController::class, 'view']);
        Route::get('/application/review/{id}', [ApplicationReviewController::class, 'viewById']);
        Route::get('/application/reviews/{coe_id}/{patient_id}', [ApplicationReviewController::class, 'viewByCoe']);
        Route::put('/application/reviews/{id}', [ApplicationReviewController::class, 'update']);

        // PATEINT ROUTES
        Route::put('/enroll', [EnrollmentController::class, 'completeEnrollment']);

        // USER ROUTES
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/profile', [UserController::class, 'profile']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users', [UserController::class, 'update']);
        Route::patch('/users/{user_id}/roles', [UserController::class, 'updateRoles']);

        // CHF-ADMIN ROUTES
        Route::get('/chfadmin/users', [ResourcePatientController::class, 'index']);
        Route::get('/chfadmin/recommendations', [RecommendationController::class, 'index']);
        Route::post('/chfadmin/approve_fund', [FundController::class, 'approveFund']);
        Route::post('/chfadmin/recommend_patient', [FundController::class, 'recommendPatient']);
        Route::get('/applications', [ApplicationController::class, 'index']);
        Route::get('/applications/search/{application_id}', [ApplicationController::class, 'search']);

        // COE-STAFF ROUTES
        Route::post('/coestaff/patient/review', [ResourceCOEStaffController::class, 'reviewPatient']);
        Route::get('/coestaff/patient/{patient_id}', [ResourcePatientController::class, 'view']);
        Route::post('/coestaff/billings', [BillingController::class, 'store']);
        Route::post('/coestaff/drugbillings', [BillingController::class, 'drugStore']);
        Route::get('/coestaff/{coestaff_id}/transactions', [ResourceCOEStaffController::class, 'billingHistory']);
        Route::get('/coestaff/patients', [ResourceCOEStaffController::class, 'patients']);
        // SUPERADMIN ROUTES
        Route::get('/transactions/dispute', [TransactionController::class, 'getTransactionDisputes']);
        Route::get('/transactions/{transaction_id}', [TransactionController::class, 'getSingleTransaction']);
        Route::post('/transactions/dispute', [TransactionController::class, 'dispute']);
        Route::put('/transactions/dispute/{transaction_id}', [TransactionController::class, 'resolveDispute']);
        Route::post('/sadmin/split', [SplitController::class, 'split']);


        // CHF SECRETARIAT ADMIN CONTROLLER
        Route::get('/sadmin/chfstaffs', [CHFAdminController::class, 'index']);
        Route::get('/sadmin/chfstaffs/{user_id}', [CHFAdminController::class, 'view']);
        Route::get('/sadmin/chfstaffs/search/{search_value}', [CHFAdminController::class, 'search']);
        Route::post('/sadmin/chfstaffs', [CHFAdminController::class, 'store']);
        Route::put('/sadmin/chfstaffs/{user_id}', [CHFAdminController::class, 'update']);
        Route::delete('/sadmin/chfstaffs/{user_id}', [CHFAdminController::class, 'destroy']);

        // COE HELP DESK STAFF CONTROLLER
        Route::get('/sadmin/coehelpdeskstaffs', [COEHelpDeskController::class, 'index']);
        Route::get('/sadmin/coehelpdeskstaffs/{user_id}', [COEHelpDeskController::class, 'view']);

        /* MANAGE COE STAFF ROUTES */
        Route::put('/coeadmin/staff/{staff_id}', [ManageStaffController::class, 'updateStaffDetail']);

        Route::get('/sadmin/coehelpdeskstaffs/search/{search_value}', [COEHelpDeskController::class, 'search']);
        Route::post('/sadmin/coehelpdeskstaffs', [COEHelpDeskController::class, 'store']);
        Route::put('/sadmin/coehelpdeskstaffs/{user_id}', [COEHelpDeskController::class, 'update']);
        Route::delete('/sadmin/coehelpdeskstaffs/{user_id}', [COEHelpDeskController::class, 'destroy']);

        /* VERIFICATION TOKEN ROUTES */
        Route::post('/token/create', [TokenController::class, 'sendToken']);
        Route::post('/token/verify', [TokenController::class, 'verifyToken']);

        /*
        *   PROTECT THESE ROUTES USING PERSONAL ACCESS TOKENS. USER MUST BE LOGGED IN
        */
        // Route::group(['middleware' => ['auth:api']], function () {

        // COE-ADMIN ROUTES
        Route::get('/coeadmin/patient', [COEAdminPatientController::class, 'index']);
        Route::post('/coeadmin/patient/review', [COEAdminPatientController::class, 'reviewPatient']);
        Route::post('/coeadmin/fund-retrieval', [FundRetrievalController::class, 'store']);
        Route::post('/fund-retrieval/{id}/approve', [FundRetrievalController::class, 'approve']);
        Route::get('/coe/{coe_id}/fund-retrieval', [FundRetrievalController::class, 'getCOEFundRetrievals']);
        // COE-STAFF ROUTES
        Route::get('/coestaff/patient/{patient_id}', [ResourcePatientController::class, 'view']);
        Route::post('/coestaff/billings', [BillingController::class, 'store']);
        Route::post('/coestaff/drugbillings', [BillingController::class, 'drugStore']);
        Route::get('/coestaff/{coestaff_id}/transactions', [ResourceCOEStaffController::class, 'billingHistory']);
        /**
         * FIND ALL THE PATIENTS UNDER THE AUTHENTICATED USER'S HOSPITAL
         */
        Route::get('/coe/patients', [ResourceCOEStaffController::class, 'coePatients']);
        /**
         * FIND ALL PATIENTS ACCESSIBLE BY THE MDT STAFF UNDER THE AUTHENTICATED USER'S HOSPITAL
         */
        Route::get('/coe/patients/mdt', [ResourceCOEStaffController::class, 'coeMDTPatients']);
        Route::post('/mdt/recommend_fund', [ResourceCOEStaffController::class, 'mdtRecommendFund']);

        /* MDT ROUTES */
        Route::get('/mdt/comments/{chf_id}', [MDTCommentController::class, 'index']);
        Route::post('/mdt/comments', [MDTCommentController::class, 'store']);
        /* FMOH FINANCE AND ACCOUNTING ROUTES */
        Route::get('/report/transactions/consolidated', [COEBillingController::class, 'consolidated']);
        Route::post('/fanda/payment/initiate', [PaymentController::class, 'initiate']);
        Route::get('/fanda/payment', [PaymentController::class, 'index']);
        Route::post('/fanda/payment/recommend', [PaymentController::class, 'recommend']);
        Route::post('/fanda/payment/approve', [PaymentController::class, 'dhsApproval']);
        Route::post('/fanda/payment/dfa/approve', [PaymentController::class, 'dfaApproval']);
        Route::post('/fanda/payment/permsec/approve', [PaymentController::class, 'permsecApproval']);
        /*
            *   RESOURCE ROUTES
            */

        /* TRANSACTION DISPUTE CHAT RESOURCE */
        Route::get('/disputes', [TransactionDisputeController::class, 'index']);
        Route::get('/transactions/{transaction_id}/dispute/comments', [TransactionDisputeController::class, 'comments']);
        Route::post('/transactions/{transaction_id}/dispute/comments', [TransactionDisputeController::class, 'storeComment']);

        /* PATIENT RESOURCES */
        Route::get('/patients/filter', [ResourcePatientController::class, 'filter']);
        Route::get('/patients/billing_history/{patient_id?}', [ResourcePatientController::class, 'billingHistory']);
        Route::get('/patients', [ResourcePatientController::class, 'index']);
        Route::get('/patients/{patient_id}', [ResourcePatientController::class, 'view']);
        Route::put('/patients/{patient_id}', [ResourcePatientController::class, 'update']);
        Route::get('/patients/search/{patient_id}', [ResourcePatientController::class, 'search']);
        Route::get('/patients/only/{patient_id}', [ResourcePatientController::class, 'viewPatient']);
        Route::get('/patients/{patient_id}/comments', [PatientCommentController::class, 'index']);
        Route::get('/patients/billing_history/{patient_id}/range', [PatientBillingController::class, 'range']);
        // ROLES RESOURCES
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{role_id}', [RoleController::class, 'view']);
        Route::put('/roles/{role_id}', [RoleController::class, 'update']);
        Route::delete('/roles/{role_id}', [RoleController::class, 'destroy']);

        Route::delete('/roles/{role_id}/permissions/{permission_id}', [RoleController::class, 'detachPermission']);
        Route::post('/roles/{role_id}/permissions/{permission_id}', [RoleController::class, 'attachPermission']);
        Route::post('/roles/parent', [RoleController::class, 'attachParent']);
        Route::get('/parent/roles', [RoleController::class, 'childRoles']);
        Route::delete('/parent/roles/{parent_id}/{role_id}', [RoleController::class, 'detachParent']);

        // SERVICES RESOURCES
        Route::get('/services', [ResourceServiceController::class, 'index']);
        Route::post('/services', [ResourceServiceController::class, 'store']);
        Route::delete('/services/{coe_id}', [ResourceServiceController::class, 'destroy']);
        Route::put('/services/{coe_id}', [ResourceServiceController::class, 'update']);
        Route::delete('/services/{service_id}/coes/{coe_id}', [ResourceServiceController::class, 'detachCoe']);
        Route::post('/services/{service_id}/coes/{coe_id}', [ResourceServiceController::class, 'attachCoe']);
        Route::put('/services/{service_id}/coes/{coe_id}', [ResourceServiceController::class, 'updateCoePrice']);


        /* SERVICE CATEGORY */
        Route::get('/service_categories', [ServiceCategoryController::class, 'index']);
        Route::post('/service_categories', [ServiceCategoryController::class, 'store']);
        Route::delete('/service_categories/{service_category_id}', [ServiceCategoryController::class, 'destroy']);
        Route::put('/service_categories/{service_category_id}', [ServiceCategoryController::class, 'update']);
        Route::delete('/service_categories/{service_category_id}/roles/{roles}', [ServiceCategoryController::class, 'detachRole']);
        Route::post('/service_categories/{service_category_id}/roles/{role_id}', [ServiceCategoryController::class, 'attachRole']);

        /* AILMENTS RESOURCES */
        Route::get('/ailments', [AilmentController::class, 'index']);
        Route::post('/ailments', [AilmentController::class, 'store']);
        Route::put('/ailments/{ailment_id}', [AilmentController::class, 'update']);
        Route::delete('/ailments/{ailment_id}', [AilmentController::class, 'destroy']);
        Route::get('/ailments/{ailment_id}', [AilmentController::class, 'view']);

        /* IDENTIFICATION DOCUMENTS */

        // CEO
        Route::get('/coes/patients', [ResourceCOEController::class, 'patients']);
        Route::get('/coes/{coe_id}', [ResourceCOEController::class, 'view']);
        Route::post('/coes', [ResourceCOEController::class, 'store']);
        Route::delete('/coes/{coe_id}', [ResourceCOEController::class, 'destroy']);
        Route::put('/coes/{coe_id}', [ResourceCOEController::class, 'updateCOE']);

        // CEO STAFF CONTROLLER
        Route::get('/coestaffs/{coe_id}', [ResourceCOEStaffController::class, 'index']);
        Route::get('/coestaffs/{coe_id}/{user_id}', [ResourceCOEStaffController::class, 'view']);
        Route::post('/coestaffs', [ResourceCOEStaffController::class, 'store']);
        Route::put('/coestaffs/{user_id}', [ResourceCOEStaffController::class, 'update']);
        Route::delete('/coestaffs/{user_id}', [ResourceCOEStaffController::class, 'destroy']);

        // CHF AUDIFOTR AND APPROVALS ROUTES
        Route::get('/chfstaffs', [ResourceCHFStaffController::class, 'index']);
        Route::get('/chfstaffs/{user_id}', [ResourceCHFStaffController::class, 'view']);
        Route::get('/chfstaffs/search/{search_value}', [ResourceCHFStaffController::class, 'search']);
        Route::post('/chfstaffs', [ResourceCHFStaffController::class, 'store']);
        Route::put('/chfstaffs/{user_id}', [ResourceCHFStaffController::class, 'update']);
        Route::delete('/chfstaffs/{user_id}', [ResourceCHFStaffController::class, 'destroy']);

        // All patients registered to a COE as their primary COE
        Route::get('/coes/{coe_id}/transactions', [ResourceCOEController::class, 'transactions']);

        // PERMISSIONS RESOURCES
        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::post('/permissions', [PermissionController::class, 'store']);
        Route::delete('/permissions/{permission_id}', [PermissionController::class, 'destroy']);
        Route::put('/permissions/{permission_id}', [PermissionController::class, 'update']);

        /* GEOPOLITICAL ZONES */
        Route::get('/geopoliticalzones', [GeopoliticalZoneController::class, 'index']);
        Route::post('/geopoliticalzones', [GeopoliticalZoneController::class, 'store']);
        Route::put('/geopoliticalzones/{geopolitical_zone_id}', [GeopoliticalZoneController::class, 'update']);
        Route::delete('/geopoliticalzones/{geopolitical_zone_id}', [GeopoliticalZoneController::class, 'destroy']);
        Route::get('/geopoliticalzones/{geopolitical_zone_id}', [GeopoliticalZoneController::class, 'view']);

        /* NIGERIA STATES ROUTES */
        Route::get('/states', [StateController::class, 'index']);
        Route::post('/states', [StateController::class, 'store']);
        Route::put('/update/{state_id}', [StateController::class, 'update']);
        Route::delete('/update/{state_id}', [StateController::class, 'update']);
        Route::get('/update/{state_id}', [StateController::class, 'view']);

        /* LGA ROUTES */
        Route::get('/lga', [LGAController::class, 'index']);
        Route::post('/lga', [LGAController::class, 'store']);
        Route::put('/lga/{lga_id}', [LGAController::class, 'update']);
        Route::delete('/lga/{lga_id}', [LGAController::class, 'destroy']);
        Route::get('/lga/{lga_id}', [LGAController::class, 'view']);

        // PATIENT
        Route::get('/patients', [ResourcePatientController::class, 'index']);

        // POOL RESOURCE
        Route::post('/pool/credit', [PoolController::class, 'store']);

        //BILLING SUMMARY REPORT RESOURCE
        Route::get('/report/billing/summary', [BillingSummaryReportController::class, 'index']);
        Route::get('/report/billing/summary/billings', [BillingSummaryReportController::class, 'billingSummary']);
        Route::get('/report/billing/summary/consolidated', [BillingSummaryReportController::class, 'consolidated']);
        Route::get('/report/coes/patient_approvals', [ReportController::class, 'coePatientApprovalReport']);

        // COE BILLINGS
        Route::get('/coes/{coe_id}/billings', [COEBillingController::class, 'index']);

        // COE TRANSACTIONS
        Route::get('/coes/{coe_id}/transactions', [COEBillingController::class, 'transactions']);

        /* UI MENU RESOURCE */
        Route::get('/uimenu', [UIMenuController::class, 'index']);
        Route::post('/uimenu', [UIMenuController::class, 'store']);
        Route::put('/uimenu/{menu_id}', [UIMenuController::class, 'update']);
        Route::delete('/uimenu/{menu_id}', [UIMenuController::class, 'destroy']);

        /* SITE SETTING RESOURCE */
        Route::get('/sitesettings', [SiteSettingController::class, 'index']);
        Route::get('/sitesettings/{key}', [SiteSettingController::class, 'view']);
        Route::post('/sitesettings', [SiteSettingController::class, 'store']);
        Route::put('/sitesettings/{id}', [SiteSettingController::class, 'update']);

        /* ANALYTICS RESOURCE */
        Route::get('/analytics', [AnalyticController::class, 'index']);
        Route::get('/analytics/patients', [AnalyticController::class, 'patient']);
        Route::get('/analytics/services', [AnalyticController::class, 'service']);

        /* DFA PAYMENT ROUTES */
        // Route::get('/coes/payments/',[DFA]);

        /* NOTIFICATION RESOURCE */
        Route::get('/notification/transactions', [Notification::class, 'transaction']);


        /* PATIENT APPLICATION ROUTES */
        Route::get('/patient/application/family-history/{id}', [FamilyHistoryController::class, 'view']);
        Route::get('/patient/application/active/family-history', [FamilyHistoryController::class, 'viewActiveRecord']);
        Route::post('/patient/application/family-history', [FamilyHistoryController::class, 'store']);
        Route::put('/patient/application/family-history/{id}', [FamilyHistoryController::class, 'update']);

        Route::get('/patient/next-of-kin/{id}', [NextOfKinController::class, 'view']);
        Route::post('/patient/next-of-kin', [NextOfKinController::class, 'store']);
        Route::put('/patient/next-of-kin/{user_id}', [NextOfKinController::class, 'update']);

        Route::get('/patient/application/personal-history/{id}', [PersonalHistoryController::class, 'view']);
        Route::get('/patient/application/active/personal-history', [PersonalHistoryController::class, 'viewActiveRecord']);
        Route::post('/patient/application/personal-history', [PersonalHistoryController::class, 'store']);
        Route::put('/patient/application/personal-history/{id}', [PersonalHistoryController::class, 'update']);

        Route::get('/patient/application/personal-information/{id}', [PersonalInformationController::class, 'view']);
        Route::get('/patient/application/active/personal-information', [PersonalInformationController::class, 'viewActiveRecord']);
        Route::post('/patient/application/personal-information', [PersonalInformationController::class, 'store']);
        Route::put('/patient/application/personal-information/{id}', [PersonalInformationController::class, 'update']);

        Route::get('/patient/application/support-assessment/{id}', [SupportAssessmentController::class, 'view']);
        Route::get('/patient/application/active/support-assessment', [SupportAssessmentController::class, 'viewActiveRecord']);
        Route::post('/patient/application/support-assessment', [SupportAssessmentController::class, 'store']);
        Route::put('/patient/application/support-assessment/{id}', [SupportAssessmentController::class, 'update']);

        Route::get('/patient/application/social-condition/{id}', [SocialConditionController::class, 'view']);
        Route::get('/patient/application/active/social-condition', [SocialConditionController::class, 'viewActiveRecord']);
        Route::post('/patient/application/social-condition', [SocialConditionController::class, 'store']);
        Route::put('/patient/application/social-condition/{id}', [SocialConditionController::class, 'update']);

        Route::post('/patient/application/social-worker-assessment/review', [SocialWorkerAssessmentController::class, 'reviewPatient']);
        Route::get('/patient/application/social-worker-assessment/{id}', [SocialWorkerAssessmentController::class, 'view']);
        Route::get('/patient/application/active/social-worker-assessment', [SocialWorkerAssessmentController::class, 'viewActiveRecord']);
        Route::post('/patient/application/social-worker-assessment', [SocialWorkerAssessmentController::class, 'store']);
        Route::put('/patient/application/social-worker-assessment/{id}', [SocialWorkerAssessmentController::class, 'update']);

        //Appointment schedule route
        Route::post('/schedule/coestaff/patient/appointment', [PatientAppointmentController::class, 'store']);
        Route::put('/schedule/coestaff/patient/appointment/{id}', [PatientAppointmentController::class, 'update']);
        Route::get('/schedule/coestaff/patient/appointment', [PatientAppointmentController::class, 'index']);
        Route::get('/schedule/coestaff/patient/appointment/{id}', [PatientAppointmentController::class, 'view']);
        Route::get('/schedule/coestaff/{coe_id}/patient/appointment', [PatientAppointmentController::class, 'viewByCoeId']);
        Route::get('/schedule/coestaff/patient/{id}/appointment', [PatientAppointmentController::class, 'viewByPatientId']);
        // });


        Route::post('/wallet-topup', [WalletTopupController::class, 'initiate']);
                Route::post('/wallet-topup/credit', [WalletTopupController::class, 'creditWallet']);
                Route::get('/wallet-topup/{id}', [WalletTopupController::class, 'topUpHistory']);

            });
            Route::get('/generate-passport-keys', [CustomController::class, 'generatePassportKeys']);
});
