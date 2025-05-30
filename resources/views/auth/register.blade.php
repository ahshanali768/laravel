@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center fw-bold">Register New User</h3>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="">-- Select Role --</option>
                                <option value="agent" @if(old('role')=='agent') selected @endif>Agent</option>
                            </select>
                            @error('role')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                            @error('username')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}">Already registered?</a>
                        </div>
                    </form>
                    <div class="alert alert-info mt-3 text-center">
                        After registering, your account will require admin approval. You will receive an email notification once your account is approved. For questions, contact <a href="mailto:admin@acraltech.site">admin@acraltech.site</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
