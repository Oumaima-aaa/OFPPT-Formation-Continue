<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiplomeController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\IntervenantController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect('/dashboard')
        : redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::get('/company/profile', [CompanyProfileController::class, 'edit'])->name('company.profile.edit');
    Route::patch('/company/profile', [CompanyProfileController::class, 'update'])->name('company.profile.update');

    Route::middleware('permission:catalog.view')->group(function () {
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/domaines/{domaine}', [CatalogController::class, 'showDomaine'])->name('catalog.domaines.show');
        Route::get('/catalog/themes/{theme}', [CatalogController::class, 'showTheme'])->name('catalog.themes.show');
    });

    Route::middleware('permission:statistics.view')->group(function () {
        Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
    });

    Route::middleware('permission:regions.view|regions.manage')->group(function () {
        Route::get('regions', [RegionController::class, 'index'])->name('regions.index');
        Route::get('regions/{region}', [RegionController::class, 'show'])->name('regions.show');
    });
    Route::middleware('permission:regions.manage')->group(function () {
        Route::resource('regions', RegionController::class)->except(['index', 'show']);
    });

    Route::middleware('permission:domaines.view|domaines.manage')->group(function () {
        Route::get('domaines', [DomaineController::class, 'index'])->name('domaines.index');
        Route::get('domaines/{domaine}', [DomaineController::class, 'show'])->name('domaines.show');
    });
    Route::middleware('permission:domaines.manage')->group(function () {
        Route::resource('domaines', DomaineController::class)->except(['index', 'show']);
    });

    Route::middleware('permission:themes.view|themes.manage')->group(function () {
        Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
        Route::get('themes/{theme}', [ThemeController::class, 'show'])->name('themes.show');
    });
    Route::middleware('permission:themes.manage')->group(function () {
        Route::resource('themes', ThemeController::class)->except(['index', 'show']);
    });

    Route::middleware('permission:etablissements.view|etablissements.manage')->group(function () {
        Route::get('etablissements', [EtablissementController::class, 'index'])->name('etablissements.index');
        Route::get('etablissements/{etablissement}', [EtablissementController::class, 'show'])->name('etablissements.show');
    });
    Route::middleware('permission:etablissements.manage')->group(function () {
        Route::resource('etablissements', EtablissementController::class)->except(['index', 'show']);
    });

    Route::middleware('permission:entreprises.manage')->group(function () {
        Route::resource('entreprises', EntrepriseController::class);
    });

    Route::middleware('permission:plans.view|plans.manage')->group(function () {
        Route::get('plans', [PlanController::class, 'index'])->name('plans.index');
        Route::get('plans/{plan}', [PlanController::class, 'show'])->name('plans.show');
    });
    Route::middleware('permission:plans.manage')->group(function () {
        Route::resource('plans', PlanController::class)->except(['index', 'show']);
        Route::patch('plans/{plan}/cancel', [PlanController::class, 'cancel'])->name('plans.cancel');
        Route::patch('plans/{plan}/approve', [PlanController::class, 'approve'])->name('plans.approve');
        Route::patch('plans/{plan}/reject', [PlanController::class, 'reject'])->name('plans.reject');
    });

    Route::middleware('permission:actions.view|actions.manage')->group(function () {
        Route::get('actions', [ActionController::class, 'index'])->name('actions.index');
        Route::get('actions/{action}', [ActionController::class, 'show'])->name('actions.show');
    });
    Route::middleware('permission:actions.manage')->group(function () {
        Route::resource('actions', ActionController::class)->except(['index', 'show']);
        Route::patch('actions/{action}/start', [ActionController::class, 'start'])->name('actions.start');
        Route::patch('actions/{action}/finish', [ActionController::class, 'finish'])->name('actions.finish');
        Route::patch('actions/{action}/cancel', [ActionController::class, 'cancel'])->name('actions.cancel');
        Route::patch('actions/{action}/approve', [ActionController::class, 'approve'])->name('actions.approve');
        Route::patch('actions/{action}/reject', [ActionController::class, 'reject'])->name('actions.reject');
        Route::post('actions/{action}/assign', [ActionController::class, 'assign'])->name('actions.assign');
        Route::patch('actions/{action}/intervenants/{intervenant}/reject', [ActionController::class, 'rejectIntervenant'])->name('actions.intervenants.reject');
    });

    Route::middleware('permission:intervenants.manage')->group(function () {
        Route::resource('intervenants', IntervenantController::class);
    });

    Route::middleware('permission:competences.manage')->group(function () {
        Route::resource('competences', CompetenceController::class);
    });

    Route::middleware('permission:diplomes.manage')->group(function () {
        Route::resource('diplomes', DiplomeController::class);
    });

    Route::middleware('permission:certifications.manage')->group(function () {
        Route::resource('certifications', CertificationController::class);
    });

    Route::middleware('permission:users.view')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
