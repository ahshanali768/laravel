<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="fw-bold mb-4" style="color:#2a5298;">Admin Reports</h2>
    <div class="row g-4 mb-3">
        <div class="col-md-3">
            <label>Date Range</label>
            <input type="date" class="form-control" id="filterStartDate">
        </div>
        <div class="col-md-3">
            <label>&nbsp;</label>
            <input type="date" class="form-control" id="filterEndDate">
        </div>
        <div class="col-md-3">
            <label>Status</label>
            <select class="form-select" id="filterStatus">
                <option value="">All</option>
                <option value="Pending">Pending</option>
                <option value="Verified">Verified</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Campaign</label>
            <select class="form-select" id="filterCampaign"><option value="">All</option></select>
        </div>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary me-2" id="applyFilters">Apply Filters</button>
    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Leads</h5>
                    <h2 class="fw-bold" id="totalLeads">-</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Approved</h5>
                    <h2 class="fw-bold text-success" id="approvedLeads">-</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Rejected</h5>
                    <h2 class="fw-bold text-danger" id="rejectedLeads">-</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Conversion Rate</h5>
                    <h2 class="fw-bold text-info" id="conversionRate">-</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Leads by Status</h5>
                    <canvas id="leadsStatusChart" height="120"></canvas>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Leads by Day (Last 30 Days)</h5>
                    <canvas id="leadsByDayChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Leads by Agent</h5>
                    <canvas id="leadsAgentChart" height="120"></canvas>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Leads by Campaign</h5>
                    <canvas id="leadsCampaignChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Lead Activity</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Lead</th>
                                    <th>Status</th>
                                    <th>Agent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="recentActivityTable">
                                <tr><td colspan="5" class="text-center">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let reportFilters = { start_date: '', end_date: '', status: '', campaign: '' };
function fetchReportsData() {
    let params = new URLSearchParams(reportFilters).toString();
    fetch('/admin/reports/data?' + params)
        .then(res => res.json())
        .then(function(data) {
            document.getElementById('totalLeads').textContent = data.total_leads;
            document.getElementById('approvedLeads').textContent = data.approved_leads;
            document.getElementById('rejectedLeads').textContent = data.rejected_leads;
            document.getElementById('conversionRate').textContent = data.conversion_rate + '%';
            // Status chart
            new Chart(document.getElementById('leadsStatusChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: data.status_labels,
                    datasets: [{
                        data: data.status_counts,
                        backgroundColor: ['#2a5298','#28a745','#dc3545','#ffc107'],
                    }]
                },
                options: {responsive:true, plugins:{legend:{position:'bottom'}}}
            });
            // Agent chart
            new Chart(document.getElementById('leadsAgentChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.agent_labels,
                    datasets: [{
                        label: 'Leads',
                        data: data.agent_counts,
                        backgroundColor: '#2a5298',
                    }]
                },
                options: {responsive:true, plugins:{legend:{display:false}}}
            });
            // Campaign chart
            new Chart(document.getElementById('leadsCampaignChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.campaign_labels,
                    datasets: [{
                        label: 'Leads',
                        data: data.campaign_counts,
                        backgroundColor: '#28a745',
                    }]
                },
                options: {responsive:true, plugins:{legend:{display:false}}}
            });
            // By day chart
            new Chart(document.getElementById('leadsByDayChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.date_labels,
                    datasets: [{
                        label: 'Leads',
                        data: data.date_counts,
                        borderColor: '#2a5298',
                        backgroundColor: 'rgba(42,82,152,0.1)',
                        fill: true,
                    }]
                },
                options: {responsive:true, plugins:{legend:{display:false}}}
            });
            // Recent Activity Table
            let tbody = document.getElementById('recentActivityTable');
            tbody.innerHTML = '';
            if(data.recent_activity.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No recent activity.</td></tr>';
            } else {
                data.recent_activity.forEach(function(act) {
                    tbody.innerHTML += `<tr>
                        <td>${act.date}</td>
                        <td>${act.lead}</td>
                        <td>${act.status}</td>
                        <td>${act.agent}</td>
                        <td>${act.action}</td>
                    </tr>`;
                });
            }
            // Populate filter dropdowns
            let campaignSel = document.getElementById('filterCampaign');
            campaignSel.innerHTML = '<option value="">All</option>' + data.campaign_labels.map(c => `<option value="${c}">${c}</option>`).join('');
        });
}
document.getElementById('applyFilters').onclick = function() {
    reportFilters.start_date = document.getElementById('filterStartDate').value;
    reportFilters.end_date = document.getElementById('filterEndDate').value;
    reportFilters.status = document.getElementById('filterStatus').value;
    reportFilters.campaign = document.getElementById('filterCampaign').value;
    fetchReportsData();
};
document.addEventListener('DOMContentLoaded', fetchReportsData);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ahshanali768/laravel/resources/views/admin-reports.blade.php ENDPATH**/ ?>