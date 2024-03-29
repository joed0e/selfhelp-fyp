<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    public function logout(Request $request)
    {
        $user = \App\User::find($request->user()->id);
        $user->status = 0;
        $user->save();
        $this->performLogout($request);
        return redirect()->route('login', ['logout' => 1]);
    }

    protected function authenticated(Request $request, $user)
    {
        $user = \App\User::find($user->id);
        $user->status = 1;
        $user->save();
        if ($request->user()->userRole == 1) {
            return redirect()->route('admin.index');
        }
        else if ($request->user()->userRole == 2){
            return redirect()->route('counsellor.index');
        }
        else {
            return redirect()->route('student.index');
        }
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(){
        return 'uniId';
    }
}
