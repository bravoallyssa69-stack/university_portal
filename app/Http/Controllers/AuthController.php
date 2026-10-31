<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AccountUsedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'student_id' => [
                'required',
                'string',
                'max:50',
                'unique:users,student_id',
                'regex:/^[a-zA-Z0-9\-]+$/'
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\.\-\ñ\Ñ]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'
            ],
            'course' => ['required', 'string'],
            'block' => ['required', 'string'],
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'student_id.required' => 'Student ID is required.',
            'student_id.unique' => 'This Student ID is already registered.',
            'student_id.regex' => 'Student ID can only contain letters, numbers, and hyphens.',
            
            'name.required' => 'Full name is required.',
            'name.min' => 'Please enter a valid full name.',
            'name.regex' => 'Invalid name format. Numbers and special characters are not allowed in the name field.',
            
            'email.required' => 'Email address is required.',
            'email.regex' => 'Registration failed: You must use a valid @gmail.com address.',
            'email.unique' => 'This Gmail address is already registered in our system.',
            
            'course.required' => 'Please select your course or program.',
            'block.required' => 'Please select your block or section.',
            
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        User::create([
            'student_id' => $request->student_id,
            'name' => trim($request->name),
            'email' => strtolower($request->email),
            'course' => $request->course,
            'block' => $request->block,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully! Please sign in with your Gmail.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'
            ],
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'email.regex' => 'Please enter a valid @gmail.com email address.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'This Gmail address is not registered in our records.',
            ]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $authUser = Auth::user();
            
            $authUser->update([
                'last_login_at' => now(),
            ]);

            // Clean try-catch to prevent login blocking if mail fluctuates
            try {
                $authUser->notify(new AccountUsedNotification());
            } catch (\Exception $e) {
                \Log::error('Mail sending failed: ' . $e->getMessage());
            }

            return redirect()->intended('dashboard');
        }

        return back()->withInput($request->only('email'))->withErrors([
            'password' => 'Incorrect password provided. Please try again.',
        ]);
    }

    public function dashboard()
    {
        $students = User::where('role', 'student')->get();
        $users = User::all();

        if (Auth::user()->role === 'admin') {
            return view('admin.dashboard', compact('users', 'students'));
        }

        return view('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Logged out successfully.');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.regex' => 'Please enter a valid @gmail.com address.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'
        ], [
            'email.regex' => 'Please enter a valid @gmail.com address.'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }
}