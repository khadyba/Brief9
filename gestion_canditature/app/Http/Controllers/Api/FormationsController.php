<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Formations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormationCreate;

class FormationsController extends Controller
{
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
}
