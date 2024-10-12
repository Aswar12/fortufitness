<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\User;

class RegisterController extends Controller
{


    protected $redirectTo = '/';
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'emergency_contact' => ['required', 'string'],

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'role' => 'member'
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}
