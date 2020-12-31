<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }

            $users = User::paginate(10);
            $pageName = 'Users';
            $roles = Role::get()->pluck('name', 'name');

            return view('admin.users.index', compact('users','pageName','roles'));
        }catch (\Exception $exception)
        {
            return view('errors.500')->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }
            $roles = Role::get()->pluck('name', 'name');
            $pageName = 'Add User';

            return view('admin.users.create', compact('roles', 'pageName'));
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Store a newly created User in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }



            $user = new User;
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->admin_unique_key = $this->_random_str(60);


            $user->save();

            $roles = $request->input('roles') ? $request->input('roles') : ['attendee'];
            $user->assignRole($roles);

            return $this->userList();

            return redirect()->route('admin::users.index')->with(['success' => 'User created successfully']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }


    /**
     * Show the form for editing User.
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

            $user = User::with('roles')->findOrFail($id);


            return \request()->json(200,['user'=>$user]);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Update User in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try{


            if (! Gate::allows('users_manage')) {
                return abort(401);
            }

            $user = User::findOrFail($id);
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            $roles = $request->input('roles') ? $request->input('roles') : [];
            $user->syncRoles($roles);
            return $this->userList();

//            return redirect()->route('admin::users.index')->with(['success' => 'User updated successfully']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            if (! Gate::allows('users_manage')) {
                return abort(401);
            }
            $user = User::findOrFail($id);
            $user->delete();
            return $this->userList();

            return redirect()->route('admin::users.index')->with(['success' => 'User deleted successfully']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Return All User
     */
    public function userList()
    {

        $user= User::with('roles')
            ->orderBy('id','DESC')
            ->paginate(10);

        return \request()->json(200,['users'=>$user]);
    }

    public function userRoles($id)
    {
        $user=User::findOrFail($id);
        $roles =$user->roles->pluck('name');
        return \request()->json(200,['roles'=>$roles]);
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {

        try{
            if (! Gate::allows('users_manage')) {
                return abort(401);
            }
            if ($request->input('ids')) {
                $entries = User::whereIn('id', $request->input('ids'))->get();

                foreach ($entries as $entry) {
                    $entry->delete();
                }
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    protected function _random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {

        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

}
