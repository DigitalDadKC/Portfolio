<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\EstimatingController;

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

Route::get('/', [WelcomeController::class, 'welcome'])->name('home');
Route::post('/contact', ContactController::class)->name('contact');
Route::get('/datatable', [DatatableController::class, 'index'])->name('datatable.index');
Route::get('/datatable/export', [DatatableController::class, 'export'])->name('export');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/estimating', [EstimatingController::class, 'index'])->name('estimating.index');
Route::get('estimating/create', [EstimatingController::class, 'create'])->name('estimating.create');
Route::post('estimating/store', [EstimatingController::class, 'store'])->name('estimating.store');
Route::get('estimating/edit/{job}', [EstimatingController::class, 'edit'])->name('estimating.edit');
Route::post('estimating/update/{job}/{pdf}', [EstimatingController::class, 'update'])->name('estimating.update');
Route::get('estimating/report', [EstimatingController::class, 'report'])->name('estimating.report');
Route::resource('/company', CompanyController::class);
Route::get('estimating/download-pdf/{job}', [EstimatingController::class, 'downloadPDF'])->name('downloadPDF.pdf');
Route::get('estimating/browser-pdf/{job}', [EstimatingController::class, 'browserPDF'])->name('browserPDF.pdf');
Route::get('/test', function () {
    return Inertia::render('test');
});

// 2 METHODS OF CREATING SYMLINKS
// Route::get('/link', function () {
//     Artisan::call('storage:link');
// });

// Route::get('/link', function () {
//    $target = '/home/dh_2gujjy/raleighgroesbeck-app/storage/app/public';
//    $shortcut = '/home/dh_2gujjy/raleighgroesbeck.com/storage';
//    symlink($target, $shortcut);
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('/skills', SkillController::class);
    Route::resource('/projects', ProjectController::class);
    Route::post('/projects/sort', [ProjectController::class, 'sort'])->name('projects.sort');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
