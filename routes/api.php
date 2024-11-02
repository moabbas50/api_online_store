<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductImagesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ShipingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::post('/user/register', [AuthController::class, 'registerUser']);
Route::post('/user/login', [AuthController::class, 'loginUser']);
// Route::post('/admin/register', [AuthController::class, 'registerAdmin']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
Route::middleware('auth:sanctum')->group(function () {
    // /////////////////////Admin/////////////////////////////////
    Route::get('/admin', [AdminsController::class, 'index']);
    Route::post('/admin/register', [AdminsController::class, 'store']);
    Route::post('/admin/restpassword', [AdminsController::class, 'rest_password']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post("/user/Update", [UserController::class, 'update']);
    Route::post('/user/restpassword', [UserController::class, 'rest_password']);
    /////////////////////products api Rout///////////////////////////
    Route::prefix("products")->group(function () {
        Route::get("/", [ProductsController::class, 'index']);
        Route::get("/{id}", [ProductsController::class, 'show']);
        Route::get("/cat_products/{id}", [ProductsController::class, 'category_products']);
        Route::get("/high_rate/products", [ProductsController::class, 'high_rate']);
        Route::post("/", [ProductsController::class, 'store']);
        Route::post("/{id}", [ProductsController::class, 'update']);
        Route::delete("/{id}", [ProductsController::class, 'destroy']);
    });

    /////////////////////products images api Rout///////////////////////////
    Route::prefix("product_image")->group(function () {
        Route::get("/{id}", [ProductImagesController::class, 'index']);
        Route::post("/{id}", [ProductImagesController::class, 'store']);
        Route::put("/update/{id}", [ProductImagesController::class, 'update']);
        Route::delete("/{id}", [ProductImagesController::class, 'destroy']);
    });
    /////////////////////categories api Rout/////////////////////////////
    Route::prefix("categories")->group(function () {
        Route::get("/", [CategoriesController::class, 'index']);
        Route::get("/{id}", [CategoriesController::class, 'show']);
        Route::post("/", [CategoriesController::class, 'store']);
        Route::post("/{id}", [CategoriesController::class, 'update']);
        Route::delete("/{id}", [CategoriesController::class, 'destroy']);
    });
    /////////////////////////////cartitem///////////////////////////////
    Route::prefix('cart_items')->group(function () {
        Route::get('/', [CartItemsController::class, 'index']);           // Show cart content
        Route::post('/{id}', [CartItemsController::class, 'store']); // create and Update cart item with product id
        Route::delete('/{id}', [CartItemsController::class, 'destroy']); // Delete cart item
    });
    ////////////////////////////////////orders//////////////////////////
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrdersController::class, 'index']);
        Route::get('/{id}', [OrdersController::class, 'vieworderitem']); /////show all items in order using ordr id
        Route::post('/', [OrdersController::class, 'store']);
        Route::post('/{id}', [OrdersController::class, 'update']); ////update status for cancelled or completed
        Route::delete('/{id}', [OrdersController::class, 'destroy']);
    });
    /////////////////////////////shiping////////////////////////////////
    Route::prefix('shiping')->group(function () {
        Route::get('/', [ShipingController::class, 'index']);
        Route::post('/', [ShipingController::class, 'store']);
        Route::post("/{id}", [ShipingController::class, 'update']); ////need shiping id that i want to update
        Route::delete('/{id}', [ShipingController::class, 'destroy']);
    });
    /////////////////////////////reviews///////////////////////////////
    Route::prefix('reviews')->group(function () {
        Route::get('/{id}', [ReviewsController::class, 'showallreviews']); /////need product id
        Route::post('/{id}', [ReviewsController::class, 'store']); /////need product id
        Route::post("/{id}", [ReviewsController::class, 'update']); ////need review id that i want to update
        Route::delete('/{id}', [ReviewsController::class, 'destroy']); ////need review id

    });
});
