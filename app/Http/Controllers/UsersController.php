<?php

namespace App\Http\Controllers;

use App\Models\OrganisationMasterModel;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function getOrganisationDetails()
    {
        $data = OrganisationMasterModel::all();
        return view('admin.User.createUser', ['data' => $data]);
    }
    public function getOrganisationCode($id)
    {
        $organisation = OrganisationMasterModel::find($id);

        if ($organisation) {
            return response()->json(['organisation_code' => $organisation->code]);
        } else {
            return response()->json(['organisation_code' => null]);
        }
    }
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'organisation_id' => 'required',
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
        return redirect('show-user');
    }
    public function showUser(Request $request)
    {
        try {
            if ($request->ajax()) {
                $showGst = User::select('*');

                return DataTables::of($showGst)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
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

    public function editUser($id)
    {
        try {
            $editUser = User::find(decrypt($id));
            $data = OrganisationMasterModel::all();

        } catch (Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('admin.User.editUser', ['editUser' => $editUser, 'data' => $data]);
    }


    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $updateUser = User::find(decrypt($id));
            $updateUser->name = $request->get('name');
            $updateUser->password = $request->get('password');
            $updateUser->updated_by = auth()->id();
            $updateUser->updated_at = now();
            $updateUser->save();
            DB::commit();
        } catch (Exception $exception) {

            DB::rollback();
            return back()->withError($exception->getMessage())->withInput();
        }
        Session::flash('message', 'User Updated Successfully.!');
        return redirect('show-user');

    }

    public function destroyUser($id)
    {
        DB::beginTransaction();
        try {
            $deleteUser = User::find($id);
            $deleteUser->delete();
            DB::commit();
        } catch (Exception $exception) {

            DB::rollback();
            return back()->withError($exception->getMessage())->withInput();
        }
        Session::flash('message', 'User Deleted Successfully.!');
        return redirect('show-user');
    }
}
