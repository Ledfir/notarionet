<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Front\EmailsAppController;
use App\Http\Controllers\Front\ContractsController;
use App\Http\Controllers\Front\OrdersController;
use App\Http\Controllers\Front\AmhaController;
use App\Http\Controllers\Front\SandybeachController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'home']);
Route::get('/admin', [HomeController::class, 'admin']);

//urls para documents e imagenes
Route::get('/img/{key}', [MediaController::class, 'getImage']);
Route::get('/docs/{key}', [MediaController::class, 'getDocument']);

Route::get('/newOrderEmail/{id}', [EmailsAppController::class, 'newOrder']);
Route::post('/signatureOrderEmail', [EmailsAppController::class, 'signatureOrder']);
Route::post('/userCreditsEmail', [EmailsAppController::class, 'sendEmail']);
Route::post('/saveOrderImage', [EmailsAppController::class, 'saveOrderImage']);

Route::post('/resedEmail', [EmailsAppController::class, 'resendEmails']);


Route::get('/testt', [HomeController::class, 'testt']);
Route::get('/gethash', [OrdersController::class, 'getHash']);


Route::post('/contrato_amha', [AmhaController::class, 'store']);
Route::get('/contrato_amha/{id}', [AmhaController::class, 'show']);

Route::post('/contrato_sandybeach', [SandybeachController::class, 'store']);

Route::put('/contrato_sandybeach_actualizar', [SandybeachController::class, 'update']);

Route::post('/mexican_individual/playapalmeras', [SandybeachController::class, 'storeMexicanindivualPlayapalmeras']);
Route::post('/mexican_individual/sterligndev', [SandybeachController::class, 'storeMexicanindivualSterligndev']);
Route::post('/mexican_individual/resell', [SandybeachController::class, 'storeMexicanindivualResell']);

Route::post('/mexican_corporation/playapalmeras', [SandybeachController::class, 'storeCorporationPlayapalmeras']);
Route::post('/mexican_corporation/sterligndev', [SandybeachController::class, 'storeCorporationSterligndev']);
Route::post('/mexican_corporation/resell', [SandybeachController::class, 'storeCorporationResell']);

Route::post('/foreigner/playapalmeras', [SandybeachController::class, 'storeForeignerPlayapalmeras']);
Route::post('/foreigner/sterligndev', [SandybeachController::class, 'storeForeignerSterligndev']);
Route::post('/foreigner/resell', [SandybeachController::class, 'storeForeignerResell']);


Route::get('/get_contract/{id}', [SandybeachController::class, 'show']);
Route::get('/testlogin', [OrdersController::class, 'testlogin']);