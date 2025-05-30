@extends('layouts.app')
@section('content')
@php use App\Helpers\PhoneMaskHelper; @endphp
<div class="container pt-5 mt-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">My Leads</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Created Date</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>DID</th>
                            <th>Campaign</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td>{{ $lead->created_at->format('d-M-Y h:i A') }}</td>
                            <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                            <td>{{ App\Helpers\PhoneMaskHelper::mask($lead->phone) }}</td>
                            <td>{{ App\Helpers\PhoneMaskHelper::maskDid($lead->did_number) }}</td>
                            <td>{{ $lead->campaign_name }}</td>
                            <td>{{ $lead->status }}</td>
                            <td>{{ $lead->notes }}</td>
                            <td>{{ $lead->agent_name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No leads found for this period.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
