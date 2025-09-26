<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/contactform', [App\Http\Controllers\Front\PageController::class, 'contact']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(["auth:sanctum"])->group(function(){
    /*Route::get('/userfront', function (Request $request) {
        $user = $request->user();
aa
        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'lastname' => $user->lastname,
            'phone' => $user->phone,
            'email' => $user->email,
            'access' => $user->access,
            'role' => $user->roles()->first()->name,
            'address' => [
                'id' => $user->address->id,
                'street' => $user->address->street,
                'num_ext' => $user->address->num_ext,
                'num_int' => $user->address->num_int,
                'neighborhood' => $user->address->neighborhood,
                'zipcode' => $user->address->zipcode,
                'country' => $user->address->country,
                'state_id' => $user->address->state_id,
                'town_id' => $user->address->town_id,
                'user_id' => $user->id
            ]
        ];
        return $response;
    });*/

    Route::get('/userfront', [App\Http\Controllers\Front\CustomerController::class, 'getData']);
    Route::post('/user/profile', [App\Http\Controllers\Front\CustomerController::class, 'profile']);
    Route::post('/user/documentation', [App\Http\Controllers\Front\CustomerController::class, 'documentation']);
    Route::post('/user/signature', [App\Http\Controllers\Front\CustomerController::class, 'signature']);
    Route::post('/user/image', [App\Http\Controllers\Front\CustomerController::class, 'image']);
    Route::post('/user/password', [App\Http\Controllers\Front\CustomerController::class, 'password']);
    Route::get('/user/paymenthistory', [App\Http\Controllers\Front\CustomerController::class, 'paymentHistory']);

    Route::get('/user/saveSignatureContracts/{id}', [App\Http\Controllers\Front\ContractsController::class, 'saveSignatureContracts']);
    Route::get('/user/cancelContracts/{id}', [App\Http\Controllers\Front\ContractsController::class, 'cancelContracts']);
    Route::post('/user/sedEmail', [App\Http\Controllers\Front\ContractsController::class, 'sedEmail']);



    Route::post('/stripe/Installments', [App\Http\Controllers\Front\CreditsController::class, 'stripeInstallments']);
    Route::post('/credits', [App\Http\Controllers\Front\CreditsController::class, 'saveOrder']);
    Route::post('/creditsdeposit', [App\Http\Controllers\Front\CreditsController::class, 'saveOrderDeposit']);

    Route::delete('/user/contacts/{id}', [App\Http\Controllers\Front\CustomerController::class, 'deleteContact']);

});//sanctum

Route::post('/user/register', [App\Http\Controllers\Front\CustomerController::class, 'store']);

Route::get('/states', [App\Http\Controllers\StateController::class, 'index']);
Route::get('/towns/{state_id}', [App\Http\Controllers\TownController::class, 'index']);

Route::get('/categoriesContracts', [App\Http\Controllers\Front\ContractsController::class, 'categoriesContracts']);
Route::get('/contracts/{id}', [App\Http\Controllers\Front\ContractsController::class, 'detail']);
Route::get('/contracts', [App\Http\Controllers\Front\ContractsController::class, 'contracts']);
Route::post('/contracts_search', [App\Http\Controllers\Front\ContractsController::class, 'search']);

Route::post('/contact', [App\Http\Controllers\Front\CustomerController::class, 'storeContact']);
Route::get('/contacts', [App\Http\Controllers\Front\CustomerController::class, 'contactsUser']);
Route::post('/chceckcontacts', [App\Http\Controllers\Front\CustomerController::class, 'checkContact']);
Route::post('/saveorder', [App\Http\Controllers\Front\OrdersController::class, 'saveOrder']);
Route::post('/saveorderimage', [App\Http\Controllers\Front\OrdersController::class, 'saveOrderImage']);

Route::get('/mycontracts', [App\Http\Controllers\Front\CustomerController::class, 'mycontracts']);
Route::get('/contractsvalidate/{id}', [App\Http\Controllers\Front\OrdersController::class, 'validateOrder']);


Route::get('/banners', [App\Http\Controllers\BannersController::class, 'indexFront']);
Route::get('/aviso', [App\Http\Controllers\ExtrasController::class, 'getPrivacidad']);
Route::get('/terminos', [App\Http\Controllers\ExtrasController::class, 'getTerminos']);

Route::post('/getreview', [App\Http\Controllers\Front\ContractsController::class, 'getReview']);

Route::get('/packages', [App\Http\Controllers\PackagesController::class, 'index']);
Route::post('/resetpassword', [App\Http\Controllers\Front\CustomerController::class, 'resetpassword']);
Route::post('/invoices', [App\Http\Controllers\InvoiceController::class, 'store']);

Route::get('/confirmemail/{id}', [App\Http\Controllers\Front\CustomerController::class, 'confirmEmail']);
Route::post('/comparesignature', [App\Http\Controllers\Front\ContractsController::class, 'compareSiganature']);

Route::get('/myfolders', [App\Http\Controllers\Front\FolderController::class, 'index']);
Route::post('/user/folders', [App\Http\Controllers\Front\FolderController::class, 'store']);
Route::post('/user/foldersmove', [App\Http\Controllers\Front\FolderController::class, 'moveDoc']);
Route::delete('/user/folders/{id}', [App\Http\Controllers\Front\FolderController::class, 'destroy']);
