<script>
var leadsAgentLabels = <?php echo json_encode($topAgents->pluck('agent_name')->toArray()); ?>;
var leadsAgentData = <?php echo json_encode($topAgents->pluck('total_leads')->toArray()); ?>;
var leadsCampaignLabels = <?php echo json_encode($campaigns->pluck('name')->toArray()); ?>;
var leadsCampaignData = <?php echo json_encode($campaigns->pluck('leads_count')->toArray()); ?>;
var leadsByDayLabels = <?php echo json_encode($dateLabels); ?>;
var leadsByDayData = <?php echo json_encode($dateCounts); ?>;
</script>
<?php /**PATH /home/ahshanali768/laravel/resources/views/_admin-dashboard-chart-vars.blade.php ENDPATH**/ ?>