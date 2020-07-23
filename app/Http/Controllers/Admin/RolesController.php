<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }

            $roles = Role::all();
            $pageName = 'Roles';

            return view('admin.roles.index', compact('roles', 'pageName'));
        }catch (\Exception $exception)
        {
            return view('errors.500')->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }
            $permissions = Permission::get()->pluck('name', 'name');
            $pageName = 'Add Roles';

            return view('admin.roles.create', compact('permissions', 'pageName'));
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }
            $request->validate([
                'name' => 'required|max:50',
                'permission' => 'required|exists:permissions,name',
            ]);
            $role = Role::create($request->except('permission'));
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $role->givePermissionTo($permissions);

            return redirect()->route('admin::roles.index')->with(['success' => 'Role created successfully']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }


    /**
     * Show the form for editing Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }
            $permissions = Permission::get()->pluck('name', 'name');
            $pageName = 'Edit Roles';

            $role = Role::findOrFail($id);

            return view('admin.roles.edit', compact('role', 'permissions', 'pageName'));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Update Role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }
            $request->validate([
                'name' => 'required|max:50',
                'permission' => 'required|exists:permissions,name',
            ]);
            $role = Role::findOrFail($id);
            $role->update($request->except('permission'));
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $role->syncPermissions($permissions);

            return redirect()->route('admin::roles.index')->with(['success' => 'Role updated successfully']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }


    /**
     * Remove Role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin::roles.index')->with(['success' => 'Role deleted successfully']);
    }

    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Role::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
