<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController ;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FormationsController;
use App\Http\Controllers\Api\CandidatsPostulerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//  les routes de l'administrateur
Route::middleware('auth')->group(function(){ 
    Route::get('/candidats',[UserController::class, 'candidats'])->middleware('isAdmin');
    Route::get('/dashbordAdmin',[UserController::class, 'index']);
    Route::post('/formations/store',[FormationsController::class, 'store'])->middleware('isAdmin');
    Route::put('/formations/edit/{formation}',[FormationsController::class,'update'])->middleware('isAdmin');
    Route::delete('/formations/destroy/{formation}',[FormationsController::class,'destroy'])->middleware('isAdmin');
    // route pour la listes des candidatures
    Route::get('/formations/candidatureList',[CandidatsPostulerController::class,'candidatureList'])->middleware('isAdmin');
// rout pour accepter ou refuser un candidature
    Route::post('/candidatureList/{candidatPost}',[CandidatsPostulerController::class,'edit'])->middleware('isAdmin');
// route  pour lister les candidature Accepter 
Route::get('/candidature/Accepter',[CandidatsPostulerController::class,'candidatureAccepter'])->middleware('isAdmin');
//  route pour les candidature refuser candidatureRefuser
Route::get('/candidature/Refuser',[CandidatsPostulerController::class,'candidatureRefuser'])->middleware('isAdmin');

    // route pour postuler a une formation
    Route::post('/formation/candidat/{formation}',[CandidatsPostulerController::class,'create']);
});

// les route des candidats  pour la liste des formations
Route::get('/formations',[FormationsController::class, 'home']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    // route pour enregistrer un utilisateur
    Route::post('register', [UserController::class,'register']);
// rout pour connecter un utilisateur
Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout']);
Route::post('refresh', [AuthController::class,'refresh']);
Route::post('me', [AuthController::class,'me']);
});