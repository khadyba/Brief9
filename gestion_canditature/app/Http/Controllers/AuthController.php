<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Requests\LoginUser;

/**
 * @OA\Info(title="Authentification avec JWT", version="0.1")
 */
class AuthController extends Controller
{
  /**
     * Create a new AuthController instance.
     *
     * @return void
     */
  
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

         /**
 * @OA\Post(
 *     path="/api/auth/login",
 *     sumary="ici on va connecter l'utilisateur a l'aide de jwt avec les token"
 *     @OA\Response(response="200", description="L'utilisateur est connecter")
 * )
 */
    public function login(LoginUser $credentials)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
       
        return $this->respondWithToken($token);

        
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
/*
     * @OA\Post(
     *     path="/api/auth/logout",
     *     sumary="ici on va deconnecter l'utilisateur"
     *     @OA\Response(response="200", description="L'utilisateur est deconnecter")
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */


     /*
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     sumary="rafraichir le token"
     *     @OA\Response(response="200", description="success")
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

     
     /*
     * @OA\Post(
     *     path="/api/auth/me",
     *     
     *     @OA\Response(response="200", description="success")
     * )
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
