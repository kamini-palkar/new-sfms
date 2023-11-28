<?php

namespace App\Http\Controllers;

use App\Models\FileUploadModel;
use App\Models\OrganisationMasterModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Exception;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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
        $files = $request->file('name', []);

        $fileCount = count($files);

        if ($fileCount > 0) {

            foreach ($files as $file) {
                $add = new FileUploadModel;
                $add->name = $file->getClientOriginalName();
                $add->org_code = auth()->user()->organisation_code;
                $uniqueId = $this->generateUniqueId($add->org_code);
                $currentYear = now()->year;
                $currentMonth = now()->month;

                $publicPath = public_path("Organisation-Code/{$add->org_code}/{$currentYear}/{$currentMonth}");

                if (!File::isDirectory($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }
                $file->move($publicPath, $file->getClientOriginalName());
                $add->unique_id = $uniqueId;
                $add->created_by = auth()->id();
                $add->updated_by = auth()->id();
                $add->created_at = now();
                $add->updated_at = now();
                $add->save();
            }
            $url = "http://files.seqr.info";

            $regardsName = auth()->user()->name;
            $id_for_mail = auth()->user()->organisation_id;

            $organisation_name = DB::table('organisation_master')->where('id', $id_for_mail)->get();
            $nameForMail = $organisation_name[0]->name;

            $email = $request->input('email');

            $emails = explode(',', $email);
            $validatedEmails = array_map('trim', $emails);
            $validatedEmails = array_filter($validatedEmails, 'filter_var', FILTER_VALIDATE_EMAIL);
            
            $data["title"] = "$nameForMail Sent You Files";
            $data["body"] = "$fileCount Files have been uploaded. Please log in to the  $url for more Details.";
            $data["regardsName"] = $regardsName;

            Mail::send('demoMail', $data, function ($message) use ($data,  $validatedEmails, $regardsName) {
                $message->to( $validatedEmails,  $validatedEmails)
                    ->subject($data["title"]);

            });
        } else {
            Session::flash('message', 'Mail not sent.');
            return redirect('show-files');
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
