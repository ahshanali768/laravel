<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $leadQuery = Lead::query();
        if ($startDate) {
            $leadQuery->whereDate('leads.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $leadQuery->whereDate('leads.created_at', '<=', $endDate);
        }

        $totalUsers = User::count();
        $totalLeads = $leadQuery->count();
        $totalCompletedLeads = (clone $leadQuery)->where('status', 'Completed')->count();
        $totalPendingLeads = (clone $leadQuery)->where('status', 'Pending')->count();
        $totalApprovedLeads = (clone $leadQuery)->where('status', 'Approved')->count();
        $totalRejectedLeads = (clone $leadQuery)->where('status', 'Rejected')->count();
        $conversionRate = $totalLeads > 0 ? round(($totalApprovedLeads / $totalLeads) * 100, 2) : 0;

        // Calculate total revenue (sum payout_amount for approved leads)
        $totalRevenue = (clone $leadQuery)
            ->where('status', 'Approved')
            ->join('campaigns', 'leads.campaign_name', '=', 'campaigns.name')
            ->sum('campaigns.payout_amount');

        // Leads by agent
        $topAgents = (clone $leadQuery)
            ->select('agent_name')
            ->selectRaw('COUNT(*) as total_leads')
            ->groupBy('agent_name')
            ->orderByDesc('total_leads')
            ->limit(10)
            ->get();

        // Leads by campaign
        $campaigns = \App\Models\Campaign::withCount('leads')->orderBy('name')->get();

        // Leads by day (last 30 days)
        $dateLabels = [];
        $dateCounts = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateLabels[] = $date;
            $dateCounts[] = (clone $leadQuery)->whereDate('created_at', $date)->count();
        }

        $recentLeads = (clone $leadQuery)->orderByDesc('leads.id')->limit(50)->get();
        $recentActivity = [
            'Lead John Doe added to Campaign Alpha',
            'Campaign Beta launched',
            'Agent Jane Smith closed a deal',
        ];
        $days = $dateLabels;
        $chartData = $dateCounts;
        return view('admin-dashboard', compact(
            'totalUsers', 'totalLeads', 'totalCompletedLeads', 'totalPendingLeads',
            'totalApprovedLeads', 'totalRejectedLeads', 'conversionRate',
            'dateLabels', 'dateCounts', 'topAgents', 'campaigns',
            'recentLeads', 'totalRevenue', 'recentActivity', 'days', 'chartData',
            'startDate', 'endDate'
        ));
    }

    public function userApprovals()
    {
        $pendingUsers = User::where('status', 'pending')->with('roles')->get();
        return view('admin-user-approvals', compact('pendingUsers'));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();
        return redirect()->back()->with('success', 'User approved successfully.');
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();
        return redirect()->back()->with('success', 'User rejected.');
    }

    public function userManagement()
    {
        $users = User::with('roles')->orderByDesc('created_at')->get();
        return view('admin-user-management', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function leadsManagement()
    {
        $leads = \App\Models\Lead::orderByDesc('created_at')->get();
        return view('admin-leads-management', compact('leads'));
    }

    public function deleteLead($id)
    {
        $lead = \App\Models\Lead::findOrFail($id);
        $lead->delete();
        return redirect()->back()->with('success', 'Lead deleted successfully.');
    }

    public function campaignsManagement()
    {
        $campaigns = \App\Models\Campaign::orderByDesc('created_at')->get();
        return view('admin-campaigns-management', compact('campaigns'));
    }

    public function createCampaign()
    {
        return view('admin-campaigns-create');
    }

    public function storeCampaign(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'payout_amount' => 'required|numeric|min:0',
        ]);
        \App\Models\Campaign::create($request->only('name', 'payout_amount'));
        return redirect()->route('admin.campaigns.management')->with('success', 'Campaign created.');
    }

    public function editCampaign($id)
    {
        $campaign = \App\Models\Campaign::findOrFail($id);
        return view('admin-campaigns-edit', compact('campaign'));
    }

    public function updateCampaign(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'payout_amount' => 'required|numeric|min:0',
        ]);
        $campaign = \App\Models\Campaign::findOrFail($id);
        $campaign->update($request->only('name', 'payout_amount'));
        return redirect()->route('admin.campaigns.management')->with('success', 'Campaign updated.');
    }

    public function deleteCampaign($id)
    {
        $campaign = \App\Models\Campaign::findOrFail($id);
        $campaign->delete();
        return redirect()->back()->with('success', 'Campaign deleted.');
    }

    public function postLeadStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $status = strtolower($request->input('status'));
        $allowed = ['verified', 'approved', 'rejected'];
        if (!in_array($status, $allowed)) {
            return response()->json(['success' => false, 'error' => 'Invalid status'], 400);
        }
        $lead->status = ucfirst($status);
        $lead->save();
        return response()->json(['success' => true]);
    }

    public function editLead($id)
    {
        $lead = \App\Models\Lead::findOrFail($id);
        // Return JSON for AJAX popup
        return response()->json(['success' => true, 'lead' => $lead]);
    }

    public function updateLead(Request $request, $id)
    {
        $lead = \App\Models\Lead::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'did_number' => 'required|string',
            'campaign_name' => 'nullable|string',
            'agent_name' => 'required|string',
            'verifier_name' => 'nullable|string',
            'status' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'email' => 'nullable|email',
            'notes' => 'nullable|string',
        ]);
        $lead->update($validated);
        return response()->json(['success' => true, 'lead' => $lead]);
    }

    public function reportsData(Request $request)
    {
        $query = \App\Models\Lead::query();
        // Filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('campaign')) {
            $query->where('campaign_name', $request->input('campaign'));
        }

        // Lead summary
        $totalLeads = $query->count();
        $approvedLeads = (clone $query)->where('status', 'Approved')->count();
        $rejectedLeads = (clone $query)->where('status', 'Rejected')->count();
        $verifiedLeads = (clone $query)->where('status', 'Verified')->count();
        $pendingLeads = (clone $query)->where('status', 'Pending')->count();

        // Leads by status
        $statusLabels = ['Pending', 'Verified', 'Approved', 'Rejected'];
        $statusCounts = [];
        foreach ($statusLabels as $label) {
            $statusCounts[] = (clone $query)->where('status', $label)->count();
        }

        // Leads by agent
        $agents = \App\Models\Lead::select('agent_name')
            ->groupBy('agent_name')
            ->pluck('agent_name');
        $agentLabels = $agents->toArray();
        $agentCounts = [];
        foreach ($agentLabels as $agent) {
            $agentCounts[] = (clone $query)->where('agent_name', $agent)->count();
        }

        // Leads by campaign
        $campaigns = \App\Models\Lead::select('campaign_name')
            ->groupBy('campaign_name')
            ->pluck('campaign_name');
        $campaignLabels = $campaigns->toArray();
        $campaignCounts = [];
        foreach ($campaignLabels as $campaign) {
            $campaignCounts[] = (clone $query)->where('campaign_name', $campaign)->count();
        }

        // Recent activity (last 10 leads)
        $recentLeads = (clone $query)->orderByDesc('updated_at')->limit(10)->get();
        $recentActivity = [];
        foreach ($recentLeads as $lead) {
            $recentActivity[] = [
                'date' => $lead->updated_at ? $lead->updated_at->format('Y-m-d H:i') : '',
                'lead' => $lead->first_name . ' ' . $lead->last_name,
                'status' => $lead->status,
                'agent' => $lead->agent_name,
                'action' => 'Updated',
            ];
        }

        // Conversion rate
        $conversionRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 2) : 0;

        // Date range (last 30 days)
        $dateLabels = [];
        $dateCounts = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateLabels[] = $date;
            $dateCounts[] = (clone $query)->whereDate('created_at', $date)->count();
        }

        return response()->json([
            'total_leads' => $totalLeads,
            'approved_leads' => $approvedLeads,
            'rejected_leads' => $rejectedLeads,
            'verified_leads' => $verifiedLeads,
            'pending_leads' => $pendingLeads,
            'conversion_rate' => $conversionRate,
            'status_labels' => $statusLabels,
            'status_counts' => $statusCounts,
            'agent_labels' => $agentLabels,
            'agent_counts' => $agentCounts,
            'campaign_labels' => $campaignLabels,
            'campaign_counts' => $campaignCounts,
            'date_labels' => $dateLabels,
            'date_counts' => $dateCounts,
            'recent_activity' => $recentActivity,
        ]);
    }

    // AJAX: Get user data for edit modal
    public function editUser($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $role = $user->roles->pluck('name')->first();
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'status' => $user->status,
            ]
        ]);
    }

    // AJAX: Update user data from edit modal
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => ['required', 'regex:/^(agent|admin)$/i'],
            'status' => ['required', 'regex:/^(pending|active|revoked)$/i'],
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->status = strtolower($validated['status']);
        $user->save();
        // Update role
        $role = ucfirst(strtolower($validated['role']));
        if ($user->roles->pluck('name')->first() !== $role) {
            $user->syncRoles([$role]);
        }
        return response()->json(['success' => true, 'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->pluck('name')->first(),
            'status' => ucfirst($user->status),
        ]]);
    }

    // AJAX: Get a single DID for edit modal
    public function editDid($id)
    {
        $did = \App\Models\Did::find($id);
        if (!$did) {
            return response()->json(['success' => false, 'error' => 'DID not found'], 404);
        }
        return response()->json(['success' => true, 'did' => $did]);
    }

    // AJAX: Save or update a DID (by did_number)
    public function saveDid(Request $request, $did_number)
    {
        $validated = $request->validate([
            'did_number' => 'required|string',
            'payout_amount' => 'nullable|numeric',
            'owner_campaign' => 'nullable|string',
            'campaign_payout' => 'nullable|numeric',
        ]);
        $did = \App\Models\Did::where('did_number', $did_number)->first();
        if ($did) {
            $did->update($validated);
        } else {
            $did = \App\Models\Did::create($validated);
        }
        return response()->json(['success' => true, 'did' => $did]);
    }

    // AJAX: Delete a DID
    public function deleteDid($id)
    {
        $did = \App\Models\Did::find($id);
        if (!$did) {
            return response()->json(['success' => false, 'error' => 'DID not found'], 404);
        }
        $did->delete();
        return response()->json(['success' => true]);
    }

    // Show script for admin (edit)
    public function showScript()
    {
        $script = \App\Models\SystemNote::find(1)?->content ?? '';
        return view('admin-script', compact('script'));
    }
    // Save script (admin)
    public function saveScript(Request $request)
    {
        $request->validate(['content' => 'required|string']);
        $note = \App\Models\SystemNote::firstOrCreate(['id' => 1]);
        $note->content = $request->input('content');
        $note->save();
        return redirect()->route('admin.script')->with('success', 'Script updated.');
    }
    // Show script for agent (read-only)
    public function showScriptAgent()
    {
        $script = \App\Models\SystemNote::find(1)?->content ?? '';
        return view('agent.script', compact('script'));
    }
}
