<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogHistory;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Where to redirect users after login.
     * @var string
     */
    protected static $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show login form 
     * @return void
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            // create log history
            $logHistory  = new LogHistory([
                'log_header'      => 'User Login',
                'permission_slug' => 'view system_user_history',
                'username'        => $request['email'],
                'user_id'         => 1,
                'description'     => 'This emil'. $request['email'].' have ben login',
            ]);
            $logHistory->save();

            return redirect()->route('home');
        }
        

        return back()->withErrors(['Invalid credentials.']);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
