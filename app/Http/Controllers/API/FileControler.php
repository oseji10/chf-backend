<?php

namespace App\Http\Controllers\API;

use App\Helpers\AWSHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileControler extends Controller
{
    //
    public function upload(Request $request){
        $this->validate($request,[
            'upload' => 'required|mimes:jpg,png,jpeg,pdf,doc,docx|max:10000',
        ]);

        try{
            $extension = $request->file('upload')->getClientOriginalExtension();
            $file = $request->file('upload')->path();
            $dir = 'uploads/documents/';    
            return ResponseHelper::ajaxResponseBuilder(true, 'File upload success', AWSHelper::uploadFileToS3($file, $extension,$dir));
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse('Could not upload file');
        }
    }
}
