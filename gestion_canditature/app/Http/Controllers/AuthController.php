<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\LoginUser;
use OpenApi\Annotations as OA;


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
 * Connecter un utilisateur.
 *
 * @OA\Post(
 *     path="/api/auth/login",
 *     tags={"Authentification"},
 *     summary="Connexion de l'utilisateur",
 *     description="Permet à l'utilisateur de se connecter et de récupérer le token d'authentification.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Informations de connexion de l'utilisateur",
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="utilisateur@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="motdepasse")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur connecté avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="Bearer <token>"),
 *             @OA\Property(property="token_type", type="string", example="Bearer"),
 *             @OA\Property(property="expires_in", type="integer", example=3600)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non autorisé - Erreur d'authentification",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */

    public function login(LoginUser $credentials)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
        $user= auth()->user();      
        if ($user->roles === 'admin') {
            return response()->json(["message"=>"vous êtes connecter en tant que Administrateur","data"=>$user]); 
        }else {
            return response()->json(["message"=>"vous êtes connecter en tant que candidat","data"=>$user]); 
        }
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

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
