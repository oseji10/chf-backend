<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PatientCommentController extends Controller
{
    //
    public function index($user_id)
    {
        try {
            $user = User::where('id',$user_id)->orWhereHas('patient',function($query) use($user_id){
                return $query->where('chf_id',$user_id);
            })->first();

            return ResponseHelper::ajaxResponseBuilder(true, "Comments for user ", $user->transactionComments()->groupBy('transaction_id')->paginate(30));
        
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($user_id,$comment_id)
    {
        try{
            $user = User::where('id',$user_id)->orWhereHas('patient',function($query) use($user_id){
                return $query->where('chf_id',$user_id);
            })->first();
            $comment = $user->transactionComments()->where('comment.id',$comment_id)->with('user')->with('documents')->with('commentedBy')->with('commentedBy.coe')->first();

            return ResponseHelper::ajaxResponseBuilder(true, 'Comment', [
                'comment' => $comment->comment,
                'created_at' => $comment->created_at,
                'commented_by' => $comment->commentedBy->first_name . ' ' . $comment->commentedBy->last_name,
                'coe_name' => $comment->commentedBy->coe->coe_name,
                'documents' => $comment->documents,

            ]);
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }
}
