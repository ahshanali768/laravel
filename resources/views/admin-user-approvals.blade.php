{{-- Unified SaaS CRM theme applied --}}
@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold" style="color:#2a5298;">Pending User Approvals</h2>
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->pluck('name')->first() }}</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="d-inline ms-2">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No pending users.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
