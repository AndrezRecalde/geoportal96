<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\AuthService;
class AuthController extends Controller
{
    public function __construct(){
    }
    public function loginIndex(Request $request){
        if(\Auth::check()){
            return redirect('/dashboard');
        }
        return view('login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!\Auth::attempt($credentials))
            return response()->json([
                'error' => __('Credenciales incorrectas')
            ], 401);

        
        return response()->json(['estado'=>'success']);
    }
    public function logout(Request $request)
    {
        \Session::flush();
        
        \Auth::logout();

        return redirect('/login');

    }
}
