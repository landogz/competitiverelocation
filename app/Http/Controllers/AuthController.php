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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to the intended URL if it exists, otherwise to dashboard
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
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

            if (!$user) {
                // Return success even if user doesn't exist (security best practice)
                return response()->json(['status' => 'passwords.sent']);
            }

            // For development environment, we'll skip the email sending
            if (app()->environment('local')) {
                // Generate a token
                $token = Str::random(64);
                
                // Store the token in the password_reset_tokens table
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $request->email],
                    [
                        'token' => Hash::make($token), // Hash the token before storing
                        'created_at' => now()
                    ]
                );
                
                // Return success with the token (only in development)
                return response()->json([
                    'status' => 'passwords.sent',
                    'debug_token' => $token // Send the raw token to the user
                ]);
            }

            // In production, use the standard password reset flow
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => $status])
                : response()->json(['status' => $status], 400);
        } catch (\Exception $e) {
            \Log::error('Password reset link error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending the password reset link. Please try again.'
            ], 500);
        }
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset', ['token' => $request->token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            // For development environment, handle the reset manually
            if (app()->environment('local')) {
                $resetToken = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->first();

                if (!$resetToken || !Hash::check($request->token, $resetToken->token)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid or expired reset token.'
                    ], 400);
                }

                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'User not found.'
                    ], 400);
                }

                $user->password = Hash::make($request->password);
                $user->save();

                // Delete the used token
                DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Your password has been reset successfully.',
                    'redirect' => url('/')
                ]);
            }

            // In production, use the standard password reset flow
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Your password has been reset successfully.',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to reset password. The reset link may have expired.'
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while resetting your password. Please try again.'
            ], 500);
        }
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
