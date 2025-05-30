{{-- Agent Dashboard --}}
@extends('layouts.app')
@section('content')
<div class="container-fluid py-4" style="max-width:1440px;">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 mb-4">
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm" style="background:#2267E3; color:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(32,40,70,0.06);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-person-lines-fill display-5 mb-2"></i>
                    <div class="text-uppercase fw-medium" style="font-size:13px;">Total Leads</div>
                    <div class="fw-bold" style="font-size:32px;">{{ $totalLeads }}</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm" style="background:#3ED598; color:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(32,40,70,0.06);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-check-circle display-5 mb-2"></i>
                    <div class="text-uppercase fw-medium" style="font-size:13px;">Approved</div>
                    <div class="fw-bold" style="font-size:32px;">{{ $totalApproved }}</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm" style="background:#FFB300; color:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(32,40,70,0.06);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-hourglass-split display-5 mb-2"></i>
                    <div class="text-uppercase fw-medium" style="font-size:13px;">Pending</div>
                    <div class="fw-bold" style="font-size:32px;">{{ $totalPending }}</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm" style="background:#FF647C; color:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(32,40,70,0.06);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-x-circle display-5 mb-2"></i>
                    <div class="text-uppercase fw-medium" style="font-size:13px;">Rejected</div>
                    <div class="fw-bold" style="font-size:32px;">{{ $totalRejected ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm" style="background:#52A2F7; color:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(32,40,70,0.06);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-currency-dollar display-5 mb-2"></i>
                    <div class="text-uppercase fw-medium" style="font-size:13px;">Revenue</div>
                    <div class="fw-bold" style="font-size:32px;">${{ number_format($revenue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius:18px;">
                <div class="card-header bg-white border-0 fw-bold" style="color:#222846; font-size:22px; border-radius:18px 18px 0 0;">Leads Overview</div>
                <div class="card-body">
                    @if(empty($chartData) || count($chartData) === 0)
                        <div class="text-center text-muted py-5">No data available</div>
                    @else
                        <canvas id="leadsChart" height="100" aria-label="Leads Overview Chart" role="img"></canvas>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius:18px;">
                <div class="card-header bg-white border-0 fw-bold" style="color:#222846; font-size:22px; border-radius:18px 18px 0 0;">Quick Actions</div>
                <div class="card-body d-flex flex-column gap-2">
                    <a href="/agent/leads/create" class="btn btn-primary" style="height:40px; border-radius:8px;"><i class="bi bi-plus-circle me-2"></i>Add Lead</a>
                    <a href="/agent/reports" class="btn btn-outline-primary" style="height:40px; border-radius:8px;"><i class="bi bi-bar-chart-line me-2"></i>View Reports</a>
                </div>
            </div>
            <div class="card border-0 shadow-sm" style="border-radius:18px;">
                <div class="card-header bg-white border-0 fw-bold" style="color:#222846; font-size:22px; border-radius:18px 18px 0 0;">Recent Activity</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($recentActivity as $activity)
                            <li class="list-group-item small" style="font-size:15px;">{{ $activity }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4" style="border-radius:18px;">
        <div class="card-header bg-white border-0 fw-bold" style="color:#222846; font-size:22px; border-radius:18px 18px 0 0;">Recent Leads</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="border-radius:18px; overflow:hidden;">
                    <thead class="table-light">
                        <tr style="font-size:13px; text-transform:uppercase;">
                            <th>Date</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Campaign</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($recentLeads ?? []) as $lead)
                        @php
                            $badgeColor = '#B0B8C4';
                            if ($lead->status === 'approved') $badgeColor = '#3ED598';
                            elseif ($lead->status === 'pending') $badgeColor = '#B0B8C4';
                            elseif ($lead->status === 'rejected') $badgeColor = '#FF647C';
                        @endphp
                        <tr>
                            <td>{{ $lead->created_at->format('Y-m-d') }}</td>
                            <td>{{ $lead->name }}</td>
                            <td>
                                <span class="badge rounded-pill" style="background:{{ $badgeColor }};color:#fff;font-size:13px;">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td>{{ $lead->campaign->name ?? '-' }}</td>
                            <td>${{ number_format($lead->revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Floating Action Button --}}
    <button class="btn btn-primary position-fixed d-flex align-items-center justify-content-center" style="bottom:32px; right:32px; width:58px; height:58px; border-radius:50%; box-shadow:0 2px 12px rgba(34,103,227,0.18); z-index:1050;" aria-label="Add Lead">
        <i class="bi bi-plus-lg fs-3"></i>
    </button>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Output chart data as JS variables
var agentLeadsDays = @json($days);
var agentLeadsChartData = @json($chartData);
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('leadsChart');
    if (ctx && agentLeadsChartData && agentLeadsChartData.length > 0) {
        var leadsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: agentLeadsDays,
                datasets: [{
                    label: 'Leads',
                    data: agentLeadsChartData,
                    borderColor: '#2267E3',
                    backgroundColor: 'rgba(34,103,227,0.10)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { title: { display: true, text: 'Date', color: '#7B8AAB', font: { size: 13 } }, ticks: { color: '#7B8AAB', font: { size: 13 } } },
                    y: { title: { display: true, text: 'Leads', color: '#7B8AAB', font: { size: 13 } }, ticks: { color: '#7B8AAB', font: { size: 13 } } }
                }
            }
        });
    }
});
</script>
@endsection
