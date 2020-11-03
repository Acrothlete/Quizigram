<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\LoginResponse;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ResultController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//admin routes
Route::group(['prefix' => 'admin','middleware' => 'admin'], function()  
{  
    Route::get('/', [TestController::class, 'admin_landing']);
    Route::get('/create-test', [TestController::class, 'add_test']);
    Route::post('/create-test', [TestController::class, 'create_test']);
    Route::get('/test',[TestController::class, 'index']);
});

Route::group(['prefix' => 'result','middleware' => 'admin'], function()  
{  
    Route::get('/', [ResultController::class, 'index']);
    Route::get('/test/{test_id}',[ResultController::class,'generate_result'])->where('test_id', '[0-9]+');
});


//user routes
Route::group(['prefix' => 'test','middleware' => 'auth'], function()  
{  
    //route for user's test landing page
    Route::get('/{id}', [TestController::class, 'user_test_landing'])->where('id', '[0-9]+');

    //route for storing users starttest and endtest time
    Route::get('/start-test/{test_id}',[QuestionController::class, 'start_test'])->where('id', '[0-9]+');
    Route::get('/{id}/question', [QuestionController::class, 'index'])->where('id', '[0-9]+');
    Route::post('/{id}/question', [QuestionController::class, 'save_answer'])->where('id', '[0-9]+');    
    Route::post('/{id}/submit', [QuestionController::class, 'save_and_submit'])->where('id', '[0-9]+');    
    Route::get('/success', [TestController::class, 'test_success']);    
});

//Temporary Question Creation
Route::get('/create-question', [QuestionController::class, 'create_question']);
//Temporary Truncate table answer
Route::get('/truncate_answer', [QuestionController::class, 'truncate_answer_table']);


//redirect after login/register  
Route::get('/redirects', [LoginResponse::class, 'index']);

// Route::get('/result', function () {
//     return view('result');
// });

//Answer Controller
Route::post('/answer', [AnswerController::class, 'index']);

//exporting 
Route::get('/export', [TestController::class, 'export'])->name('export');