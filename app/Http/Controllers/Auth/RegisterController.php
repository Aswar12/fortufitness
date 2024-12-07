<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Arr;

class RegisterController extends Controller
{
    protected $redirectTo = '/';

    public function store(Request $request)
    {
        // Validasi yang lebih komprehensif
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'password_confirmation' => ['required', 'same:password'],
            'gender' => [
                'required',
                'in:Perempuan,Laki-laki'
            ],
            'birth_date' => [
                'required',
                'date',
                'before:' . now()->subYears(13)->format('Y-m-d'),
                'after:' . now()->subYears(100)->format('Y-m-d')
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/',
                'min:10',
                'max:15',
                'unique:users,phone_number'
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'emergency_contact' => [
                'required',

            ],


        ], [
            // Custom error messages
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi',
            'email.unique' => 'Email sudah terdaftar',
            'email.regex' => 'Format email tidak valid',
            'password.uncompromised' => 'Password terlalu umum dan tidak aman',
            'gender.in' => 'Pilih jenis kelamin yang valid',
            'birth_date.before' => 'Anda harus berusia minimal 13 tahun',
            'phone_number.unique' => 'Nomor telepon sudah terdaftar',
            'phone_number.regex' => 'Nomor telepon tidak valid'
        ]);

        try {
            // Proses pembuatan user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'gender' => $validatedData['gender'],
                'birth_date' => $validatedData['birth_date'],
                'phone_number' => $validatedData['phone_number'],
                'address' => $validatedData['address'],
                'emergency_contact' => json_encode($validatedData['emergency_contact']),
                'role' => 'member'
            ]);

            // Login otomatis setelah registrasi
            auth()->login($user);

            // Redirect dengan pesan sukses
            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            // Tangani error yang mungkin terjadi
            return back()->withErrors(['msg' => 'Registrasi gagal: ' . $e->getMessage()]);
        }
    }
}
