<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    /**
     * index of login
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view("login");
    }

    /**
     * login
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse|\never
     */
    public function login(LoginRequest $request)
    {
        if ($request->ajax()) {
            if (auth()->attempt(['email' => $request->email, 'password' => $request->password], $request->remember? true : false)) {
                $redirect = redirect()->intended(URL::route('chat.index'));
                return response()->json(['success'  => true,'message' => 'Login Berhasil', 'redirect' => $redirect->getTargetUrl()]);
            } else {
                return response()->json(['success' => false, 'message'  => 'Login Gagal Silakan Masukan Email & Password yg benar']);
            }
        }
        return abort(404);
    }
    public function store( Request $request)
    {
        # code...
       
        $validatedData = $request->validate([
            'name' => 'max:50|required',
            'email' => 'max:100|required|email',
            'password' => 'max:50|required',
           ]);
          
           $validatedData['id'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 11);
          // dd($validatedData);
          $user = User::create($validatedData);
         auth()->login($user);
        
         return redirect()->to('/chat');
    }

    public function register()
    {
        # code...
        return view("register");
    }

    /**
     * logout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();
        return redirect()->route("login");
    }
}
