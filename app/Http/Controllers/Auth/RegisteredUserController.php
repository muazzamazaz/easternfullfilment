<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    { 
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
          //  'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $password = $this->generateRandomPassword();
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'position' => $request->position,
            'volume' => $request->volume,
            'website' => $request->website,
            'status' => 0,
            'role_id'=>2,//owner
            'is_active'=>1,
            'is_deleted'=>0,
            'password' => Hash::make($password),
            'password_text' => $password,
        ]);

    //    event(new Registered($user));

        Session::put('registered_user', $user);
/*
        if ($validator->fails()){
            return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ]);
        }
  
//$data = $request->all();
     //   $user = User::create($data);
  
     //   Auth::login($user);
  */
        return response()->json([
            "status" => true, 
            "errors"=>$user,
            "redirect" => route('data2')
            ]); 
        //return redirect(route('info'));
    }

    private function generateRandomPassword($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
        $password = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }
}
