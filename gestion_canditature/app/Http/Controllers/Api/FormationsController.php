<?php
namespace App\Http\Controllers\Api;
use Exception;
use App\Models\Formations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditeFormations;
use App\Http\Requests\FormationCreate;
use OpenApi\Annotations as OA;

class FormationsController extends Controller
{
    /**
     * Récupérer la liste des formations.
     *
     * @OA\Get(
     *     path="/api/formations",
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
            public function home(){
                $formation = Formations::where('is_delete', 0)->get();
                return response()->json([
                    "status" => 1,
                    "message" => "voici la listes des  formations créer",
                    "data" => $formation
                ]);
            }
/**
 * Créer une nouvelle formation.
 *
 * @OA\Post(
 *     path="/api/formations/store",
 *     tags={"Formations"},
 *     summary="Créer une nouvelle formation",
 *     description="Permet de créer une nouvelle formation.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"libeller", "description", "durer_formations"},
 *             @OA\Property(property="libeller", type="string", example="Nom de la formation"),
 *             @OA\Property(property="description", type="LongText", example="Description de la formation"),
 *             @OA\Property(property="durer_formations", type="string", example="Durée de la formation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Formation créée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="status_message", type="string", example="Formations créée avec succès"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 ref="/components/schemas/Formations"
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
    public function store(FormationCreate  $request){
        try {
            $formation=new Formations();
            $formation->libeller= $request->libeller;
            $formation->description= $request->description;
            $formation-> durer_formations= $request-> durer_formations;
            // $formation->user_id = auth()->user()->id;
            $formation->save();
            return response()->json(
               [
                   'status_code'=>200,
                   'status_massage'=> 'Formations créer avec succéss',
                   'data'=>$formation
               ]);
         } catch (Exception $e) {
    
                return response()->json($e);
                              }
         }
/**
 * Mettre à jour une formation existante.
 *
 * @OA\Put(
 *     path="/api/formations/edit/{formation}",
 *     tags={"Formations"},
 *     summary="Mettre à jour une formation existante",
 *     description="Permet de mettre à jour une formation existante.",
 *     @OA\Parameter(
 *         name="formation",
 *         in="path",
 *         description="ID de la formation à mettre à jour",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"libeller", "description", "durer_formations"},
 *             @OA\Property(property="libeller", type="string", example="Nouveau nom de la formation"),
 *             @OA\Property(property="description", type="Longtext", example="Nouvelle description de la formation"),
 *             @OA\Property(property="durer_formations", type="string", example="Nouvelle durée de la formation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Formation modifiée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="status_message", type="string", example="Formations modifiée avec succès"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 ref="/components/schemas/Formations"
 *              
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
         public function update(EditeFormations  $request, Formations $formation) {
             try{
                 $formation->libeller=$request->libeller;
                 $formation->description=$request->description;
                 $formation->durer_formations=$request->durer_formations;
                 $formation->save();
                 return response()->json(
                     [
                         'status_code'=>200,
                         'status_massage'=> ' formations modifier  avec succéss',
                         'data'=>$formation
                        ]);
                        // dd($formation;
               }catch(Exception $e){
                          return response()->json($e);
            }
              }
              /**
 * Supprimer une formation ( delete).
 *
 * @OA\Delete(
 *     path="/api/formations/destroy/{formation}",
 *     tags={"Formations"},
 *     summary="Supprimer une formation",
 *     description="Supprime une formation de manière logique ( delete).",
 *     @OA\Parameter(
 *         name="formation",
 *         in="path",
 *         description="ID de la formation à supprimer",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Formation supprimée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="/components/schemas/Formations"))
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
              public function destroy(Formations $formation) {
                try {
                    if ($formation->is_delete === 0) {
                        $formation->is_delete = 1;
                        $formation->save();
                    }
                    $formation = Formations::where('is_delete', 0)->get();
                    return response()->json($formation);
                } catch (Exception $e) {
                    return response()->json($e);
                }
            }
}
