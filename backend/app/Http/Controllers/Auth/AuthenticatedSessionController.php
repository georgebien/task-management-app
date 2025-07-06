<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthenticatedSessionController extends Controller
{
    use ResponseTrait;

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            if (! Auth::attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            $request->session()->regenerate();

            $user = Auth::user();

            return $this->success(
                'Login successful.',
                [
                    'user' => $user,
                    'token' => $user->createToken('api-token')->plainTextToken
                ]
            );
        } catch (Throwable $th) {
            Log::error('Login failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Login failed', 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return $this->success('Logout successful.' );
        } catch (Throwable $th) {
            Log::error('Login failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Login failed', 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
