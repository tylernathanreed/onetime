<?php

use App\Http\Controllers\SecretController;

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

$router->get('/', [SecretController::class, 'index'])->name('index');
$router->post('store', [SecretController::class, 'store'])->name('store');
$router->get('secret/{secret}', [SecretController::class, 'show'])->name('show');
$router->get('reveal/{secret}', [SecretController::class, 'reveal'])->name('reveal');
$router->get('destroy/{secret}', [SecretController::class, 'destroy'])->name('destroy');
