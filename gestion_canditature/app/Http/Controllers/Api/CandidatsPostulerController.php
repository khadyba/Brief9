<?php
namespace App\Http\Controllers\Api;
use Exception;
use App\Models\User;
use App\Models\Formations;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Models\candidatPostuler;
use App\Http\Controllers\Controller;
use App\Notifications\CandidatureAccepter;
use Illuminate\Notifications\Notification;
use App\Http\Requests\CandidatsPostulerRequest;
use App\Notifications\CandidatureReçu;

class CandidatsPostulerController extends Controller
{
/**
 * Créer une candidature pour une formation.
 *
 * @OA\Post(
 *     path="/api/formation/candidat/{formationId}",
 *     tags={"Formations"},
 *     summary="Créer une candidature",
 *     description="Crée une candidature pour une formation donnée.",
 *     @OA\Parameter(
 *         name="formationId",
 *         in="path",
 *         description="ID de la formation pour laquelle la candidature est créée",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Candidature créée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="status_message", type="string", example="Votre Candidature est prise en compte"),
 *             @OA\Property(property="user", type="object", ref="/components/schemas/candidatPostuler")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Utilisateur ou formation non trouvé",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=404),
 *             @OA\Property(property="status_message", type="string", example="Utilisateur non trouvé ou Formation n'existe pas")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur")
 *         )
 *     )
 * )
 */
    public function create($formationId) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'status_message' => 'Utilisateur non trouvé'
                ], 404);
            }
            // Vérification si la formation existe
            $formation = Formations::find($formationId);
            if (!$formation) {
                return response()->json([
                    'status_code' => 404,
                    'status_message' => 'Formation exite pas'
                ], 404);
            }
            $candidater = new candidatPostuler();
            $candidater->formations_id = $formation->id;
            $candidater->user_id = $user->id;
            if ($candidater->save()) {
                $candidat = User::find($candidater['user_id']);
                $candidat->notify(new CandidatureReçu($candidater->email)); 
                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'Votre Candidature est prise en compte',
                    'user' => $candidater
                ]);
            }else {
                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'Le mail n\'a pas été envoyer',
                    'user' => $candidater
                ]);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    /**
 * Récupérer la liste des candidatures avec informations sur la formation et l'utilisateur.
 *
 * @OA\Get(
 *     path="/api/formations/candidatureList",
 *     tags={"Formations"},
 *     summary="Liste des candidatures avec informations",
 *     description="Récupère la liste de toutes les candidatures avec des informations détaillées sur la formation et l'utilisateur associé.",
 *     @OA\Response(
 *         response=200,
 *         description="Liste des candidatures récupérée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="candidatures", type="array", @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="formation", type="object", ref="/components/schemas/Formations"),
 *                 @OA\Property(property="user", type="object", @OA\Property(property="id", type="integer", example=1), @OA\Property(property="nom", type="string", example="John"), @OA\Property(property="prenom", type="string", example="Doe"), @OA\Property(property="email", type="string", example="john@example.com")))
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur")
 *         )
 *     )
 * )
 */
    public function candidatureList() {
        try {
            //  on Récupére de toutes les candidatures avec les informations nécessaires
            $candidatures = candidatPostuler::with('formation', 'user:id,nom,prenom,email')->get();
            return response()->json([
                'status_code' => 200,
                'candidatures' => $candidatures
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
/**
 * Modifier le statut d'une candidature.
 *
 * @OA\Post(
 *     path="/api/candidatureList/{candidatPost}",
 *     tags={"Candidatures"},
 *     summary="Modifier le statut d'une candidature",
 *     description="Modifie le statut d'une candidature de 'Refuser' à 'Accepter'.",
 *     @OA\Parameter(
 *         name="candidatPost",
 *         in="path",
 *         required=true,
 *         description="ID de la candidature à modifier",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Statut de la candidature modifié avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Statut de la candidature modifié avec succès"),
 *             @OA\Property(property="candidature", type="object", ref="/components/schemas/candidatPostuler")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Impossible de modifier le statut de la candidature",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Impossible de modifier le statut de la candidature")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Statut déjà accepté ou candidature non trouvée",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Statut déjà accepté ou candidature non trouvée")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur")
 *         )
 *     )
 * )
 */
    public function edit(candidatPostuler $candidatPost){
            try {
                if ($candidatPost->statut === 'Refuser') {
                    $candidatPost->statut = 'Accepter';
                    if ($candidatPost->save()) {
                        $candidat = User::find($candidatPost['user_id']);
                        $candidat->notify(new CandidatureAccepter($candidat->email));
                        return response()->json([
                            'status_code' => 200,
                            'message' => 'Statut de la candidature modifié avec succès',
                            'candidature' => $candidatPost
                        ]);
                    } else {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Impossible de modifier le statut de la candidature'
                    ]);
                }
            }else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'statut dejat accepter'
                ]);
            }
            }catch(Exception $e) {
                return response()->json($e);
            }
            
        }
        /**
 * Récupérer la liste des candidatures acceptées.
 *
 * @OA\Get(
 *     path="/api/candidature/Accepter",
 *     tags={"Candidatures"},
 *     summary="Liste des candidatures acceptées",
 *     description="Récupère la liste de toutes les candidatures avec le statut 'Accepter'.",
 *     @OA\Response(
 *         response=200,
 *         description="Liste des candidatures acceptées récupérée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="listes des candidature Accepter"),
 *             @OA\Property(
 *                 property="candidatures",
 *                 type="array",
 *                 @OA\Items(ref="/components/schemas/candidatPostuler")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur")
 *         )
 *     )
 * )
 */
            public function candidatureAccepter(){
                try {
                    $candidatures = candidatPostuler::with('formation', 'user')->where('statut', 'Accepter')->get();
                    $candidaturesDetails = $candidatures->map(function ($candidature) {
                        return [
                            'id' => $candidature->id,
                            'formation_libeller' => $candidature->formation->libeller, 
                            'utilisateur_email' => $candidature->user->email, 
                            'utilisateur_prenom' => $candidature->user->prenom, 
                            'utilisateur_nom' => $candidature->user->nom,   
                        ];
                    });
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Liste des candidatures acceptées avec détails',
                        'candidatures' => $candidaturesDetails
                    ]);
                } catch (Exception $e) {
                    return response()->json($e);
                }
            }
/**
 * Récupérer la liste des candidatures refusées.
 *
 * @OA\Get(
 *     path="/api/candidature/Refuser",
 *     tags={"Candidatures"},
 *     summary="Liste des candidatures refusées",
 *     description="Récupère la liste de toutes les candidatures avec le statut 'Refuser'.",
 *     @OA\Response(
 *         response=200,
 *         description="Liste des candidatures refusées récupérée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Liste des candidature Refuser"),
 *             @OA\Property(
 *                 property="candidatures",
 *                 type="array",
 *                 @OA\Items(ref="/components/schemas/candidatPostuler")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur")
 *         )
 *     )
 * )
 */
 public function candidatureRefuser(){
    try {
        $candidatures = candidatPostuler::with('formation', 'user')->where('statut', 'Refuser')->get();
        $candidaturesDetails = $candidatures->map(function ($candidature) {
            return [
                'id' => $candidature->id,
                'formation_libeller' => $candidature->formation->libeller, 
                'utilisateur_email' => $candidature->user->email, 
                'utilisateur_prenom' => $candidature->user->prenom, 
                'utilisateur_nom' => $candidature->user->nom,   
            ];
        });
        return response()->json([
            'status_code' => 200,
            'message' => 'Liste des candidatures refuser avec détails',
            'candidatures' => $candidaturesDetails
        ]);
    } catch (Exception $e) {
        return response()->json($e);
    }
}

        // public function candidatureRefuser(){
        //     try {
        //         //  on Récupére de toutes les candidatures avec les informations nécessaires
        //         $candidatures = candidatPostuler::where('statut', 'Refuser')->get();
        //         return response()->json([
        //             'status_code' => 200,
        //             'message' => 'listes des candidature Refuser ',
        //             'candidatures' => $candidatures
        //         ]);
        //     } catch (Exception $e) {
        //         return response()->json($e);
        //     }
        // }
    
}
