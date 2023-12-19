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

            public function home(){
                $formation = Formations::where('is_delete', 0)->get();
                return response()->json([
                    "status" => 1,
                    "message" => "voici la listes des  formations créer",
                    "data" => $formation
                ]);
              
            }


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
