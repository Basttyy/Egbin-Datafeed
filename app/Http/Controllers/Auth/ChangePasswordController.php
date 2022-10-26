<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
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

    use AuthenticatesUsers;

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
        $this->middleware('auth')->except([]);
    }
        
    /**
     * Show the password change form.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string',
            'password_confirmation' => 'required|string'
        ]);

        if ($request->password != $request->password_confirmation) {
            Log::info("password and password confirmation doesn't match");
            return redirect("/auth/change-password")->with("message", "password and password confirmation doesn't match");
        }

        if (!Hash::check($request->current_password, auth()->user()->getAuthPassword())) {
            Log::info("current password is incorrect");
            return redirect("/auth/change-password")->with("message", "current password is incorrect");
        }

        if (!$user = User::where('id', auth()->user()->getAuthIdentifier())) {
            Log::info("invalid user");
            return redirect("/auth/change-password")->with("message", "invalid user");
        }
        Log::info("all checks passed");
        $user->update(['password' => Hash::make($request->password)]);
        Log::info("password updated success");
        return redirect("/auth/change-password")->with("message", "password updated success");
    }
}
