<?php

namespace App\Http\Controllers;

use App\Models\FileUploadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FileUploadController extends Controller
{


   
public function storeFiles(Request $request)
{

    $files = $request->file('name' , []);
    if ($files) {
        foreach ($files as $file) {
            $add = new FileUploadModel;
            $add->name = $file->getClientOriginalName(); 
            $add->org_code = auth()->user()->organisation_code;
            $add->created_by = auth()->id();
            $add->updated_by = auth()->id();
            $add->created_at = now();
            $add->updated_at = now();
            $add->save();
            Session::flash('message', 'File Added Successfully!');
        }
    } else {
      
        Session::flash('message', 'No files were uploaded.');
    }
    return redirect('show-files');
}

}
