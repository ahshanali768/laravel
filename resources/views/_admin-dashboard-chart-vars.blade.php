<script>
// Dashboard chart data for Chart.js (ensured to be valid JS arrays)
var leadsAgentLabels = {!! json_encode($topAgents->pluck('agent_name')->toArray()) !!};
var leadsAgentData = {!! json_encode($topAgents->pluck('total_leads')->toArray()) !!};
var leadsCampaignLabels = {!! json_encode($campaigns->pluck('name')->toArray()) !!};
var leadsCampaignData = {!! json_encode($campaigns->pluck('leads_count')->toArray()) !!};
var leadsByDayLabels = {!! json_encode($dateLabels) !!};
var leadsByDayData = {!! json_encode($dateCounts) !!};
// All arrays above are guaranteed to be valid for Chart.js, even if empty
</script>
