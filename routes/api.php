<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
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

//Public Routes

// @type    GET
// @route   /api/products
// @desc    Show all active products
// @access  PUBLIC
Route::get('/products', [ProductController::class, 'index']);

// @type    GET
// @route   /api/products/{id}
// @desc    Show a single product based on id
// @access  PUBLIC
Route::get('/products/{id}', [ProductController::class, 'show']);

// @type    POST
// @route   /api/register
// @desc    Register user
// @access  PUBLIC
Route::POST('/register', [AuthController::class, 'register']);

// @type    POST
// @route   /api/login
// @desc    Login user
// @access  PUBLIC
Route::POST('/login', [AuthController::class, 'login']);


//Protected Routes group
Route::group(['middleware' => ['auth:sanctum']], function () {

    // @type    POST
    // @route   /api/products
    // @desc    Create product
    // @access  PROTECTED
    Route::POST('/products', [ProductController::class, 'store']);

    // @type    POST
    // @route   /api/products/{ID}
    // @desc    Update product
    // @access  PROTECTED
    Route::post('/products/{id}', [ProductController::class, 'update']);

    // @type    DELETE
    // @route   /api/products/{id}
    // @desc    Delete product
    // @access  PROTECTED
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // @type    POST
    // @route   /api/logout
    // @desc    Logout user
    // @access  PROTECTED
    Route::POST('/logout', [AuthController::class, 'logout']);

});

