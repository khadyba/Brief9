<?php
namespace App\Http\Controllers\Api;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormationCreate;
use App\Models\Formations;
use OpenApi\Annotations as OA;

class UserController extends Controller

{
      /**
     * Enregistrer un nouvel utilisateur.
     *
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Authentification"},
     *     summary="Enregistrer un nouvel utilisateur",
     *     description="Enregistre un nouvel utilisateur avec les détails requis.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom", "prenom", "email", "password"},
     *             @OA\Property(property="nom", type="string", example="Doe", description="Nom de famille de l'utilisateur."),
     *             @OA\Property(property="prenom", type="string", example="John", description="Prénom de l'utilisateur."),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com", description="Adresse email de l'utilisateur."),
     *             @OA\Property(property="password", type="string", example="MotDePasse123", description="Mot de passe de l'utilisateur.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur enregistré",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="status_message", type="string", example="Utilisateur enregistré"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="John"),
     *                 @OA\Property(property="prenom", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur Interne du Serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erreur Interne du Serveur")
     *         )
     *     )
     * )
     */
 public function register(RegisterUser  $request){
        try{
            $user = new User();
            $user->nom= $request->nom;
            $user->prenom= $request->prenom;
            $user->email= $request->email;
            $user->password= $request->password;
            $user->save();
        
            return response()->json(
              [
                  'status_code'=>200,
                  'status_massage'=> 'Utilisateur enregistré',
                  'user'=>$user
              ]);
            } catch(Exception $e){
        }
                          return response()->json($e);
        }
      /**
     * Récupérer la liste des formations.
     *
     * @OA\Get(
     *     path="/api/dashbordAdmin",
     *     tags={"Formations"},
     *     summary="Liste des formations",
     *     description="Récupère la liste de toutes les formations disponibles.",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des formations récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="message", type="string", example="Voici la liste des formations"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     ref="/components/schemas/Formations"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
        public function  index(){
            $formation = Formations::all();
          
            return response()->json([
                "status" => 1,
                "message" => "voici la listes des  formations",
                "data" => $formation
            ]);
          }
  }
