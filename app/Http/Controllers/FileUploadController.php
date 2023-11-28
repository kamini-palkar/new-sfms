<?php

namespace App\Http\Controllers;

use App\Models\FileUploadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Exception;
use Yajra\DataTables\DataTables;
class FileUploadController extends Controller
{




    private function generateUniqueId($organisationCode)
    {
        $lastRecord = FileUploadModel::where('org_code', $organisationCode)
            ->orderBy('id', 'desc')
            ->first();
    
        if ($lastRecord) {
            $lastUniqueId = intval(preg_replace('/[^0-9]/', '', $lastRecord->unique_id));
            $newUniqueId = $organisationCode . '_' . ($lastUniqueId + 1);
        } else {
            $newUniqueId = $organisationCode . '_1';
        }
    
        return $newUniqueId;
    }
    
public function storeFiles(Request $request)
{
    $files = $request->file('name' , []);
    if ($files) {
        foreach ($files as $file) {
            $add = new FileUploadModel;
            $add->name = $file->getClientOriginalName(); 
            $add->org_code = auth()->user()->organisation_code;

            $uniqueId = $this->generateUniqueId($add->org_code);
            $add->unique_id = $uniqueId;

            $add->created_by = auth()->id();
            $add->updated_by = auth()->id();
            $add->created_at = now();
            $add->updated_at = now();
            $add->save();
            Session::flash('message', 'Added Successfully!');
        }
    } else {
      
        Session::flash('message', 'No files were uploaded.');
    }
    Session::flash('message', 'File Added Successfully.!');
    return redirect('show-files');
}

public function showFile(Request $request)
{
    $org_code = auth()->user()->organisation_code;
    $user_id = auth()->id();
    try {
        if ($request->ajax()) {
            $showFile = FileUploadModel::select('*')
            ->where('created_by', $user_id)
            ->where('org_code', $org_code)
            ->get();

            return DataTables::of($showFile)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    
                    $deleteUrl = route('delete-file', ['id' => $row->id]);

                    $actionBtn = '
                 <a  href="' . $deleteUrl . '" title="Delete"   style="cursor: pointer;font-weight:normal !important;" class="menu-link flex-stack px-3"><i class="fa fa-trash" style="color:red"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])

                ->make(true);
        }
    } catch (Exception $exception) {
        return back()->withError($exception->getMessage())->withInput();
    }
    return view('admin.Files.showFile');
}



public function destroyFile($id)
{
   
    DB::beginTransaction();
    try {
        $user_id = auth()->id();
        $destroyFile = FileUploadModel::find($id);
        $destroyFile::where('id', $destroyFile->id)->update(['deleted_by' => $user_id]);
        $destroyFile->delete();
        DB::commit();
    } catch (Exception $exception) {

        DB::rollback();
        return back()->withError($exception->getMessage())->withInput();
    }
    Session::flash('message', 'File Deleted Successfully.!');
    return redirect('show-files');
}

}
