<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'organisation_id' => 'required',
            'organisation_code' => 'required',
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $storeUser = new User();
            $storeUser->organisation_id = $request->get('organisation_id');
            $storeUser->organisation_code = $request->get('organisation_code');
        
            $storeUser->name = $request->get('name');
            $storeUser->username = $request->get('username');
            $storeUser->password = $request->get('password');
            $storeUser->created_by = auth()->id();
            $storeUser->updated_by = auth()->id();
            $storeUser->created_at = now();
            $storeUser->updated_at = now();
            $storeUser->save();

            DB::commit();
        } catch (Exception $exception) {
            
            DB::rollback();
            return back()->withError($exception->getMessage())->withInput();
            
        }
        Session::flash('message', 'User Added Successfully.!'); 
        return redirect('showUser');
    }



    public function showUser(Request $request)
    {
        try {
            if ($request->ajax()) {
                $showGst = User::select('*');
                
                return DataTables::of($showGst)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $encryptedId = encrypt($row->id);
                        $editUrl = route('edit-user', ['id' => $encryptedId]);
                        $deleteUrl = route('delete-user', ['id' => $row->id]);
                       
                     $actionBtn = '<a href="' . $editUrl . '" title="Edit" class="menu-link flex-stack px-3" style="font-weight:normal !important;"><i class="fa fa-edit" id="ths" style="font-weight:normal !important;"></i></a>
                     <a  href="' . $deleteUrl . '" title="Delete"   style="cursor: pointer;font-weight:normal !important;" class="menu-link flex-stack px-3"><i class="fa fa-trash" style="color:red"></i></a>';
                     return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    
                    ->make(true);
            }
        } catch (Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
    
        return view('admin.User.showUser');
    }

}
