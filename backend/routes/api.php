<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\test;
use App\Http\Controllers\TestController;
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

Route::get('news', [TestController::class, 'index']);
 
Route::get('news/{id}', function($id) {
    return test::find($id);
});

Route::post('news', [TestController::class, 'store']);

Route::put('news/{id}', function(Request $request, $id) {
    $test = test::findOrFail($id);
    $test->update($request->all());

    return $test;
});

Route::delete('news/{id}', function($id) {
    test::find($id)->delete();

    return 204;
});
