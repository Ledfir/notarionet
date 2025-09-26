<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//grupo para añadir admin en la ruta. ejemplo api/admin/user
Route::prefix('admin')->group(function () {

	//todas las rutas de este grupo requieren session iniciada
	Route::middleware(["auth:sanctum"])->group(function(){

		//ruta para cuando se refresque la pagina
		Route::get('/user', function (Request $request) {
			$request->user()->getRoleNames();
			return $request->user();
		});

		//menu
		Route::get('/menu', [App\Http\Controllers\MenusController::class, 'index']);

		//usuarios
		Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
		Route::get('/user/{id}', [App\Http\Controllers\UserController::class, 'show']);
		Route::post('/user', [App\Http\Controllers\UserController::class, 'store']);
		Route::post('/user/{id}', [App\Http\Controllers\UserController::class, 'update']);
		Route::delete('/user/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
		Route::delete('/users', [App\Http\Controllers\UserController::class, 'destroyMultiple']);
        Route::post('/profile', [App\Http\Controllers\UserController::class, 'profile']);

		//Edicion de roles
		Route::get('/permissions', [App\Http\Controllers\RolesController::class, 'permissions']);
		Route::get('/roles', [App\Http\Controllers\RolesController::class, 'index']);
		Route::get('/role/{id}', [App\Http\Controllers\RolesController::class, 'show']);
		Route::post('/role', [App\Http\Controllers\RolesController::class, 'store']);
		Route::post('/role/{id}', [App\Http\Controllers\RolesController::class, 'update']);
		Route::delete('/role/{id}', [App\Http\Controllers\RolesController::class, 'destroy']);

		//Extras
		Route::get('/terminos', [App\Http\Controllers\ExtrasController::class, 'getTerminos']);
		Route::post('/terminos', [App\Http\Controllers\ExtrasController::class, 'updateTerminos']);
		Route::get('/privacidad', [App\Http\Controllers\ExtrasController::class, 'getPrivacidad']);
		Route::post('/privacidad', [App\Http\Controllers\ExtrasController::class, 'updatePrivacidad']);
		Route::get('/redes', [App\Http\Controllers\ExtrasController::class, 'getRedes']);
		Route::post('/redes', [App\Http\Controllers\ExtrasController::class, 'updateRedes']);
		Route::get('/faqs', [App\Http\Controllers\ExtrasController::class, 'getFaq']);
		Route::post('/faqs', [App\Http\Controllers\ExtrasController::class, 'updateFaq']);

		//Clientes
		Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index']);
		Route::get('/customers/{id}', [App\Http\Controllers\CustomerController::class, 'show']);
		Route::post('/customers', [App\Http\Controllers\CustomerController::class, 'store']);
		Route::post('/customers/{id}', [App\Http\Controllers\CustomerController::class, 'update']);
		Route::delete('/customers/{id}', [App\Http\Controllers\CustomerController::class, 'destroy']);
		Route::delete('/customers', [App\Http\Controllers\CustomerController::class, 'destroyMultiple']);

		Route::post('/customersauthorize', [App\Http\Controllers\CustomerController::class, 'authorizeCustomer']);
		Route::post('/customerscredits', [App\Http\Controllers\CustomerController::class, 'addCredits']);
		//pedidos
		Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index']);
		Route::get('/contracts_generated', [App\Http\Controllers\OrderController::class, 'indexGenerated']);


		Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show']);
		Route::post('/order/{id}', [App\Http\Controllers\OrderController::class, 'setStatus']);

		//productos
		Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);
		Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
		Route::post('/products', [App\Http\Controllers\ProductController::class, 'store']);
		Route::post('/products/{id}', [App\Http\Controllers\ProductController::class, 'update']);
		Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);
		Route::delete('/products', [App\Http\Controllers\ProductController::class, 'destroyMultiple']);
		//importar
		Route::post('/importProducts', [App\Http\Controllers\ProductController::class, 'import']);

		//categorias
		Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index']);
		Route::get('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'show']);
		Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store']);
		Route::post('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update']);
		Route::delete('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy']);
		Route::delete('/categories', [App\Http\Controllers\CategoryController::class, 'destroyMultiple']);

		//subcategorias
		Route::get('/subcategories', [App\Http\Controllers\SubcategoryController::class, 'index']);
		Route::get('/subcategories/{id}', [App\Http\Controllers\SubcategoryController::class, 'show']);
		Route::post('/subcategories', [App\Http\Controllers\SubcategoryController::class, 'store']);
		Route::post('/subcategories/{id}', [App\Http\Controllers\SubcategoryController::class, 'update']);
		Route::delete('/subcategories/{id}', [App\Http\Controllers\SubcategoryController::class, 'destroy']);
		Route::delete('/subcategories', [App\Http\Controllers\SubcategoryController::class, 'destroyMultiple']);
		Route::get('/getSubcategories/{id}', [App\Http\Controllers\SubcategoryController::class, 'getSubcategories']);

		//blogs
		Route::get('/blogs', [App\Http\Controllers\BlogController::class, 'index']);
		Route::get('/blogs/{id}', [App\Http\Controllers\BlogController::class, 'show']);
		Route::post('/blogs', [App\Http\Controllers\BlogController::class, 'store']);
		Route::post('/blogs/{id}', [App\Http\Controllers\BlogController::class, 'update']);
		Route::delete('/blogs/{id}', [App\Http\Controllers\BlogController::class, 'destroy']);
		Route::delete('/blogs', [App\Http\Controllers\BlogController::class, 'destroyMultiple']);

		//ciudades y estados
		Route::get('/states', [App\Http\Controllers\StateController::class, 'index']);
		Route::get('/towns/{state_id}', [App\Http\Controllers\TownController::class, 'index']);

        //banners
        Route::get('/banners', [App\Http\Controllers\BannersController::class, 'index']);
		Route::get('/banners/{id}', [App\Http\Controllers\BannersController::class, 'show']);
		Route::post('/banners', [App\Http\Controllers\BannersController::class, 'store']);
		Route::post('/banners/{id}', [App\Http\Controllers\BannersController::class, 'update']);
		Route::delete('/banners/{id}', [App\Http\Controllers\BannersController::class, 'destroy']);
		Route::delete('/banners', [App\Http\Controllers\BannersController::class, 'destroyMultiple']);


		//contratos
        Route::get('/contracts', [App\Http\Controllers\ContractsController::class, 'index']);
		Route::get('/contracts/{id}', [App\Http\Controllers\ContractsController::class, 'show']);
		Route::post('/contracts', [App\Http\Controllers\ContractsController::class, 'store']);
		Route::post('/contracts/{id}', [App\Http\Controllers\ContractsController::class, 'update']);
		Route::delete('/contracts/{id}', [App\Http\Controllers\ContractsController::class, 'destroy']);
		Route::delete('/contracts', [App\Http\Controllers\ContractsController::class, 'destroyMultiple']);

		Route::post('/contractsclause/{id}', [App\Http\Controllers\ContractsController::class, 'storeClause']);
		Route::delete('/contractsclause', [App\Http\Controllers\ContractsController::class, 'destroyMultipleClause']);
		Route::post('/contractsimage/{id}', [App\Http\Controllers\ContractsController::class, 'storeImage']);
		Route::delete('/contractsimage', [App\Http\Controllers\ContractsController::class, 'destroyMultipleImage']);

		//paquetes
        Route::get('/packages', [App\Http\Controllers\PackagesController::class, 'index']);
		Route::get('/packages/{id}', [App\Http\Controllers\PackagesController::class, 'show']);
		Route::post('/packages', [App\Http\Controllers\PackagesController::class, 'store']);
		Route::post('/packages/{id}', [App\Http\Controllers\PackagesController::class, 'update']);
		Route::delete('/packages/{id}', [App\Http\Controllers\PackagesController::class, 'destroy']);
		Route::delete('/packages', [App\Http\Controllers\PackagesController::class, 'destroyMultiple']);

		//facturas
        Route::get('/invoices', [App\Http\Controllers\InvoiceController::class, 'index']);
		Route::get('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'show']);
		Route::post('/invoices', [App\Http\Controllers\InvoiceController::class, 'store']);
		Route::post('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'update']);
		Route::delete('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'destroy']);
		Route::delete('/invoices', [App\Http\Controllers\InvoiceController::class, 'destroyMultiple']);


		Route::get('/userscredits', [App\Http\Controllers\CustomerController::class, 'userscredits']);
		Route::post('/userscreditsstatus', [App\Http\Controllers\CustomerController::class, 'setPayCredit']);
	});//sanctum
});



