@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4" style="color:#2a5298;">Admin Settings</h2>
    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-people-fill display-4 mb-3 text-primary"></i>
                    <h5 class="card-title mb-2">Manage Users & Roles</h5>
                    <p class="text-muted text-center mb-3">Add, edit, or remove users and assign roles.</p>
                    <a href="/admin/users" class="btn btn-outline-primary w-100">User Management</a>
                    <a href="/admin/roles" class="btn btn-outline-secondary w-100 mt-2">Role Management</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-bullseye display-4 mb-3 text-success"></i>
                    <h5 class="card-title mb-2">Manage Campaigns</h5>
                    <p class="text-muted text-center mb-3">Create, edit, or archive campaigns.</p>
                    <a href="/admin/campaigns" class="btn btn-outline-success w-100">Campaign Management</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-shield-lock display-4 mb-3 text-warning"></i>
                    <h5 class="card-title mb-2">Permissions</h5>
                    <p class="text-muted text-center mb-3">Configure role-based access and permissions.</p>
                    <a href="/admin/permissions" class="btn btn-outline-warning w-100">Permission Management</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-file-earmark-text display-4 mb-3 text-info"></i>
                    <h5 class="card-title mb-2">Script Management</h5>
                    <p class="text-muted text-center mb-3">Manage call scripts for agents.</p>
                    <a href="/admin/script" class="btn btn-outline-info w-100">Script Management</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-plug display-4 mb-3 text-primary"></i>
                    <h5 class="card-title mb-2">Integrations</h5>
                    <p class="text-muted text-center mb-3">Connect email, chat, file storage, social media, and more.</p>
                    <a href="/admin/integrations" class="btn btn-primary w-100">Manage Integrations</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection