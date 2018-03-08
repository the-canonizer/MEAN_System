<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Upload;
use Storage;
use Validator;

class UploadController extends Controller
{
    //

    public function getUpload(Request $request){
        $uploaded = Upload::where('user_id',$request->user()->id)->get();
        
        return view('upload',compact('uploaded'));
    }

    public function postUpload(Request $request){
       
       
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,bmp,png',
            'file_name' => 'required',
        ]);

        if($validator->fails()) {
             session(['error'=> "Select a image file and fill file name"]);
             return redirect()->back()->withErrors($validator);
        }

        $file = $request->file('file'); 
        if($file){
        $fileId = uniqid() . '.' . $file->getClientOriginalExtension();
        try{
            $file->storeAs('uploads',$fileId,'public');
            $upload = new Upload;
            $upload->file_id = $fileId;
            $upload->file_name = $request->input('file_name');
            $upload->file_type = $file->getMimeType();
            $upload->user_id = $request->user()->id;
            $upload->save();
           $request->session()->flash('success', 'File uploaded successfully!');
        }catch(\Exception $e){
            $request->session()->flash('error', $e->getMessage());
        }    }else{
            $request->session()->flash('error', "Select a file to upload");
        }

        return redirect()->back();
    }
}
