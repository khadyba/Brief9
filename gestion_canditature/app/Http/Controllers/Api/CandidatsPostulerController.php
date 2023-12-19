<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Models\candidatPostuler;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatsPostulerRequest;
use App\Models\Formations;
use OpenApi\Annotations as OA;



class CandidatsPostulerController extends Controller
{
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
            $candidater->save();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Votre Candidature est prise en compte',
                'user' => $candidater
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
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
    

    
    public function edit(candidatPostuler $candidatPost){
        try{
            
            // Vérification et mise à jour du statut
            // dd($candidatPost);
            if ($candidatPost->statut ==='Refuser') {
                $candidatPost->statut ='Accepter';
                $candidatPost->save();
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
        } catch(Exception $e) {
            return response()->json($e);
        }
    }
    


        public function candidatureAccepter(){
            try {
                //  on Récupére de toutes les candidatures avec les informations nécessaires
                $candidatures = candidatPostuler::where('statut', 'Accepter')->get();
                return response()->json([
                    'status_code' => 200,
                    'message' => 'listes des candidature Accepter ',
                    'candidatures' => $candidatures
                ]);
            } catch (Exception $e) {
                return response()->json($e);
            }
        }



        public function candidatureRefuser(){
            try {
                //  on Récupére de toutes les candidatures avec les informations nécessaires
                $candidatures = candidatPostuler::where('statut', 'Refuser')->get();
                return response()->json([
                    'status_code' => 200,
                    'message' => 'listes des candidature Refuser ',
                    'candidatures' => $candidatures
                ]);
            } catch (Exception $e) {
                return response()->json($e);
            }
        }
    
}
