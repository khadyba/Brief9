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

/**
 * @OA\Info(title="enpoind des utilisateur", version="0.1")
 */
class UserController extends Controller

{
        /**
 * @OA\Post(
 *     path="/api/auth/register",
 *     sumary="la fonction register permet d'enrgister un utilisateur
 *     @OA\Response(response="200", description="Utilisateur enregister")
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
        public function  index(){
          
            $formation = Formations::all();
          
            return response()->json([
                "status" => 1,
                "message" => "voici la listes des  formations",
                "data" => $formation
            ]);
          
           
}



  }
