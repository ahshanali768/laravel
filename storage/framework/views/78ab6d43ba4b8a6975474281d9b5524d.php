<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_admin-dashboard-chart-vars', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="container-fluid py-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4 mb-4">
        
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-person-lines-fill display-5 mb-2"></i>
                    <h6 class="mb-1">Total Leads</h6>
                    <div class="fs-3 fw-bold"><?php echo e($totalLeads); ?></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-check-circle display-5 mb-2"></i>
                    <h6 class="mb-1">Approved</h6>
                    <div class="fs-3 fw-bold"><?php echo e($totalApprovedLeads); ?></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-x-circle display-5 mb-2"></i>
                    <h6 class="mb-1">Rejected</h6>
                    <div class="fs-3 fw-bold"><?php echo e($totalRejectedLeads); ?></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm text-dark" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-hourglass-split display-5 mb-2"></i>
                    <h6 class="mb-1">Pending</h6>
                    <div class="fs-3 fw-bold"><?php echo e($totalPendingLeads); ?></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #396afc 0%, #2948ff 100%);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-percent display-5 mb-2"></i>
                    <h6 class="mb-1">Conversion Rate</h6>
                    <div class="fs-3 fw-bold"><?php echo e($conversionRate); ?>%</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card metric-card h-100 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #396afc 0%, #2948ff 100%);">
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                    <i class="bi bi-currency-dollar display-5 mb-2"></i>
                    <h6 class="mb-1">Revenue</h6>
                    <div class="fs-3 fw-bold">$<?php echo e(number_format($totalRevenue, 2)); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 h-100">
                <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">Leads by Agent</div>
                <div class="card-body">
                    <?php if(empty($topAgents) || $topAgents->count() === 0): ?>
                        <div class="text-center text-muted py-5">No data to display.</div>
                    <?php else: ?>
                        <canvas id="leadsAgentChart" height="180"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 h-100">
                <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">Leads by Campaign</div>
                <div class="card-body">
                    <?php if(empty($campaigns) || $campaigns->count() === 0): ?>
                        <div class="text-center text-muted py-5">No data to display.</div>
                    <?php else: ?>
                        <canvas id="leadsCampaignChart" height="180"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 h-100">
                <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">Leads by Day (Last 30 Days)</div>
                <div class="card-body">
                    <?php if(empty($dateCounts)): ?>
                        <div class="text-center text-muted py-5">No data to display.</div>
                    <?php else: ?>
                        <canvas id="leadsByDayChart" height="180"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4 align-items-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 fw-bold d-flex justify-content-between align-items-center" style="color:#2a5298;">
                    <span>Leads Overview</span>
                    <button class="btn btn-outline-primary btn-sm" id="filterBtn"><i class="bi bi-funnel"></i> Filter</button>
                </div>
                <div class="card-body">
                    <div id="filterDropdown" class="dropdown-menu p-3" style="display:none; position:absolute; left:0; top:50px; min-width:300px;">
                        <form method="GET" action="">
                            <div class="mb-2">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <input type="date" name="start_date" class="form-control mb-2" placeholder="Start date" value="<?php echo e(request('start_date', $startDate ?? '')); ?>">
                                <input type="date" name="end_date" class="form-control" placeholder="End date" value="<?php echo e(request('end_date', $endDate ?? '')); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            <?php if(request('start_date') || request('end_date')): ?>
                                <a href="<?php echo e(url()->current()); ?>" class="btn btn-link btn-sm">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <canvas id="leadsChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">Quick Actions</div>
                <div class="card-body d-flex flex-column gap-2">
                    <a href="/admin/leads/create" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Add Lead</a>
                    <a href="/admin/users/create" class="btn btn-outline-primary"><i class="bi bi-person-plus me-2"></i>Add User</a>
                    <a href="/admin/campaigns/create" class="btn btn-outline-primary"><i class="bi bi-bullseye me-2"></i>Add Campaign</a>
                    <a href="/admin/reports" class="btn btn-outline-primary"><i class="bi bi-bar-chart-line me-2"></i>View Reports</a>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">Recent Activity</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item small"><?php echo e($activity); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">Recent Leads</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>DID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recentLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($l->id); ?></td>
                            <td><?php echo e($l->created_at->format('Y-m-d')); ?></td>
                            <td><?php echo e($l->first_name); ?> <?php echo e($l->last_name); ?></td>
                            <td><?php echo e($l->phone); ?></td>
                            <td><?php echo e($l->did_number); ?></td>
                            <td><span class="badge bg-<?php echo e(strtolower($l->status)==='approved'?'success':(strtolower($l->status)==='rejected'?'danger':'secondary')); ?>"><?php echo e($l->status); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Leads by Agent
    new Chart(document.getElementById('leadsAgentChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: leadsAgentLabels,
            datasets: [{
                label: 'Leads',
                data: leadsAgentData,
                backgroundColor: '#2a5298',
            }]
        },
        options: {responsive:true, plugins:{legend:{display:false}}}
    });
    // Leads by Campaign
    new Chart(document.getElementById('leadsCampaignChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: leadsCampaignLabels,
            datasets: [{
                label: 'Leads',
                data: leadsCampaignData,
                backgroundColor: '#28a745',
            }]
        },
        options: {responsive:true, plugins:{legend:{display:false}}}
    });
    // Leads by Day (Last 30 Days)
    new Chart(document.getElementById('leadsByDayChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: leadsByDayLabels,
            datasets: [{
                label: 'Leads',
                data: leadsByDayData,
                borderColor: '#2a5298',
                backgroundColor: 'rgba(42,82,152,0.1)',
                fill: true,
            }]
        },
        options: {responsive:true, plugins:{legend:{display:false}}}
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ahshanali768/laravel/resources/views/admin-dashboard.blade.php ENDPATH**/ ?>