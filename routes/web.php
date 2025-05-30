<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/manager-dashboard', function () {
    return view('manager-dashboard');
})->middleware(['auth', 'role:manager']);

Route::get('/sales-dashboard', function () {
    return view('sales-dashboard');
})->middleware(['auth', 'role:sales']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/user-approvals', [AdminDashboardController::class, 'userApprovals'])->name('admin.users.approvals');
    Route::post('/admin/users/{id}/approve', [AdminDashboardController::class, 'approveUser'])->name('admin.users.approve');
    Route::post('/admin/users/{id}/reject', [AdminDashboardController::class, 'rejectUser'])->name('admin.users.reject');
    Route::get('/admin/users', [AdminDashboardController::class, 'userManagement'])->name('admin.users.management');
    Route::delete('/admin/users/{id}/delete', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/admin/leads', [AdminDashboardController::class, 'leadsManagement'])->name('admin.leads.management');
    Route::delete('/admin/leads/{id}/delete', [AdminDashboardController::class, 'deleteLead'])->name('admin.leads.delete');
    Route::get('/admin/campaigns', [AdminDashboardController::class, 'campaignsManagement'])->name('admin.campaigns.management');
    Route::get('/admin/campaigns/create', [AdminDashboardController::class, 'createCampaign'])->name('admin.campaigns.create');
    Route::post('/admin/campaigns/create', [AdminDashboardController::class, 'storeCampaign'])->name('admin.campaigns.store');
    Route::get('/admin/campaigns/{id}/edit', [AdminDashboardController::class, 'editCampaign'])->name('admin.campaigns.edit');
    Route::post('/admin/campaigns/{id}/edit', [AdminDashboardController::class, 'updateCampaign'])->name('admin.campaigns.update');
    Route::delete('/admin/campaigns/{id}/delete', [AdminDashboardController::class, 'deleteCampaign'])->name('admin.campaigns.delete');
    // Add view and edit routes for admin leads
    Route::get('/admin/leads/{id}/view', [AdminDashboardController::class, 'viewLead'])->name('admin.leads.view');
    Route::get('/admin/leads/{id}/edit', [AdminDashboardController::class, 'editLead'])->name('admin.leads.edit');
    Route::post('/admin/leads/{id}/edit', [AdminDashboardController::class, 'updateLead'])->name('admin.leads.update');
    Route::post('/admin/leads/{id}/status', [AdminDashboardController::class, 'postLeadStatus'])->name('admin.leads.status');
    Route::get('/admin/reports', function() { return view('admin-reports'); })->name('admin.reports');
    Route::get('/admin/reports/data', [\App\Http\Controllers\AdminDashboardController::class, 'reportsData'])->name('admin.reports.data');
    Route::get('/admin/payment', function() { return view('admin-payment'); })->name('admin.payment');
    Route::get('/admin/settings', function() { return view('admin-settings'); })->name('admin.settings');
    Route::get('/admin/users/{id}/edit', [\App\Http\Controllers\AdminDashboardController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/edit', [\App\Http\Controllers\AdminDashboardController::class, 'updateUser'])->name('admin.users.update');
    // DID AJAX CRUD
    Route::get('/admin/dids/{id}/edit', [AdminDashboardController::class, 'editDid'])->name('admin.dids.edit');
    Route::post('/admin/dids/{did_number}/edit', [AdminDashboardController::class, 'saveDid'])->name('admin.dids.save');
    Route::delete('/admin/dids/{id}/delete', [AdminDashboardController::class, 'deleteDid'])->name('admin.dids.delete');
    // Admin script management
    Route::get('/admin/script', [\App\Http\Controllers\AdminDashboardController::class, 'showScript'])->name('admin.script');
    Route::post('/admin/script', [\App\Http\Controllers\AdminDashboardController::class, 'saveScript'])->name('admin.script.save');
    Route::view('/admin/integrations', 'admin-integrations')->name('admin.integrations');
    Route::post('/admin/integrations/email', [\App\Http\Controllers\AdminDashboardController::class, 'saveEmailIntegration'])->name('admin.integrations.email.save');
});

// Redirect /admin-dashboard to /admin/dashboard for compatibility
Route::redirect('/admin-dashboard', '/admin/dashboard');

Route::middleware(['auth', 'role:agent'])->prefix('agent')->group(function () {
    Route::get('leads', [\App\Http\Controllers\AgentLeadController::class, 'index'])->name('agent.leads.index');
    Route::get('leads/create', [\App\Http\Controllers\AgentLeadController::class, 'create'])->name('agent.leads.create');
    Route::post('leads/create', [\App\Http\Controllers\AgentLeadController::class, 'store'])->name('agent.leads.store');
});

// Agent dashboard route
Route::middleware(['auth', 'role:agent'])->get('/agent/dashboard', [\App\Http\Controllers\AgentDashboardController::class, 'index'])->name('agent.dashboard');

// Agent username autocomplete endpoint
Route::get('/agent/autocomplete', [\App\Http\Controllers\AgentLeadController::class, 'autocompleteAgents'])->middleware('auth')->name('agent.autocomplete');

// Add route for AJAX DID list
Route::get('/admin/dids/list', function() {
    $dids = \App\Models\Did::select('did_number')->orderBy('did_number')->get();
    return response()->json(['dids' => $dids]);
})->middleware(['auth', 'role:admin|agent']);

// Agent script view
Route::middleware(['auth', 'role:agent'])->get('/agent/script', [\App\Http\Controllers\AdminDashboardController::class, 'showScriptAgent'])->name('agent.script');

require __DIR__.'/auth.php';
