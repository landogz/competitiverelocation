<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => implode(' ', $validator->errors()->all())
                    ], 200);
                }
                return back()->withErrors($validator)->withInput();
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                // Get sales_rep data if user is an agent
                $salesRep = null;
                if ($user->privilege === 'agent') {
                    $salesRep = DB::table('sales_reps')
                        ->where('user_id', $user->id)
                        ->first();
                }

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful',
                        'user' => [
                            'privilege' => $user->privilege,
                            'sales_rep' => $salesRep
                        ],
                        'redirect' => $request->session()->get('url.intended', '/dashboard')
                    ]);
                }

                return redirect()->intended('/dashboard');
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided credentials do not match our records.'
                ], 200);
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('email'));
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred during login. Please try again.'
                ], 200);
            }

            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.',
            ])->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    /**
     * Send a password reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->first();

            // Always return success message for security
            $message = __('If your email exists in our system, a password reset link has been sent.');
            $success = true;

            if ($user) {
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                if ($status === Password::RESET_LINK_SENT) {
                    $message = __('A password reset link has been sent to your email address.');
                } else {
                    $message = __('Unable to send reset link. Please try again.');
                    $success = false;
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => $success,
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('status', $message);
        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('An error occurred while processing your request.')
                ], 500);
            }

            return redirect()->back()->withErrors(['email' => __('An error occurred while processing your request.')]);
        }
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset', ['token' => $request->token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Always use the standard password reset flow
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Your password has been reset successfully. Please log in.');
        }

        return redirect()->back()->withErrors(['email' => 'Unable to reset password. The reset link may have expired.']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}
