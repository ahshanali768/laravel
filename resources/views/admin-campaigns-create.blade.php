{{-- Unified SaaS CRM theme applied --}}
@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold" style="color:#2a5298;">Add Campaign</h2>
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.campaigns.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Payout Amount</label>
                    <input type="number" name="payout_amount" class="form-control" step="0.01" min="0" required>
                </div>
                <button type="submit" class="btn btn-primary px-4 me-2"><i class="bi bi-plus-circle me-1"></i>Create</button>
                <a href="{{ route('admin.campaigns.management') }}" class="btn btn-outline-secondary px-4">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
