<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
    	return view('auth.login');
	}

    protected function credentials(Request $request)
    {
		$credentials = $request->only('usuario', 'password');
		return $credentials;
	}

    /**
     * Login personalizado
     * Validación de acceso al sistema según las credenciales
    */
    public function login(Request $request)
    {
        $credentials = $this->validate(request(), array(
            'usuario'   => 'required|string',
            'password'  => 'required|string'
        ), array(
            'usuario.required'  => 'Tienes que ingresar tu correo.',
            'password.required' => 'Tienes que ingresar tu contraseña.'
        ));

        $remember = request()->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('inicio');
        } else {
            return back()->withErrors(['usuario' => trans('auth.failed')])->withInput();
        }
	}

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
