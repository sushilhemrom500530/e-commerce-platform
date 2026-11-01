<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticate
{
    public function __construct(
        protected JwtService $jwtService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $token = $request->bearerToken()
            ?: ($request->cookie('token') ?: $request->cookies->get('token'));

        if (!$token && $request->hasHeader('cookie')) {
            $rawCookie = (string) $request->header('cookie');
            if (preg_match('/(?:^|;\s*)token=([^;]+)/', $rawCookie, $matches)) {
                $token = urldecode($matches[1]);
            }
        }

        if (!$token) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Access denied. No token provided.',
                'errorMessages' => [
                    ['path' => '', 'message' => 'Access denied. No token provided.'],
                ],
            ], 401);
        }

        try {
            $decoded = $this->jwtService->verifyToken($token);
        } catch (AppException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => $e->getMessage(),
                'errorMessages' => [
                    ['path' => '', 'message' => $e->getMessage()],
                ],
            ], 401);
        }

        $userId = $decoded->id ?? ($decoded->userId ?? null);
        $userRole = $decoded->role ?? null;

        // Check authorized roles
        if (!empty($roles) && !in_array($userRole, $roles, true)) {
            return response()->json([
                'success' => false,
                'statusCode' => 403,
                'message' => 'You are not authorized',
                'errorMessages' => [
                    ['path' => '', 'message' => 'You are not authorized'],
                ],
            ], 403);
        }

        // Attach user resolver so $request->user() works
        $user = $userId ? User::find($userId) : null;
        $request->setUserResolver(fn() => $user);
        $request->attributes->set('jwt_payload', $decoded);

        return $next($request);
    }
}