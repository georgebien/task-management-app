<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     * Register a user
     * 
     * @param RegisterRequest $request
     * 
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['password'] =  Hash::make($validated['password']);

            /**
             * Did not create a service/repository for this one
             * since this is only the logic that I need
             */
            $user = User::create($validated);

             return $this->success(
                'Registration successful.',
                [
                    'user' => $user,
                ]
            );
        } catch (Throwable $th) {
            Log::error('Registration failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Registration failed', 
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
            Log::error('Logout failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Logout failed', 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
