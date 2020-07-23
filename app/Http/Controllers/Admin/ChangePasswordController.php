<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;


class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Where to redirect users after password is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = 'admin/change_password';

    /**
     * Change password form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        try {
            $user = Auth::getUser();
            $pageName = 'Change Password';

            return view('admin.change_password', compact('user', 'pageName'));
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Change password.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        try
        {
            $user = Auth::getUser();
            $this->validator($request->all())->validate();
            if (Hash::check($request->get('current_password'), $user->password)) {
                $user->password = Hash::make($request->get('new_password'));
                $user->save();
                return redirect($this->redirectTo)->with(['success' => 'Password changed successfully!']);
            } else {
                return redirect()->back()->with(['danger' => 'Current password incorrect!']);
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        try{
            return Validator::make($data, [
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }
}
