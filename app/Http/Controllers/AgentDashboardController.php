<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\Campaign;
use App\Models\User;
use Carbon\Carbon;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status_filter = $request->input('status', '');
        $campaign_filter = $request->input('campaign', '');

        $campaigns = Campaign::orderBy('name')->get();

        // Fix ambiguous column by prefixing created_at with leads. in all raw queries
        $leadQuery = Lead::where('agent_name', $user->username)
            ->whereRaw("strftime('%Y-%m-%d', leads.created_at) >= ?", [$start_date])
            ->whereRaw("strftime('%Y-%m-%d', leads.created_at) <= ?", [$end_date]);
        if ($status_filter) {
            $leadQuery->where('status', $status_filter);
        }
        if ($campaign_filter) {
            $leadQuery->where('campaign_name', $campaign_filter);
        }

        $totalLeads = (clone $leadQuery)->count();
        $totalApproved = (clone $leadQuery)->where('status', 'Approved')->count();
        $totalPending = (clone $leadQuery)->where('status', 'Pending')->count();

        $revenue = (clone $leadQuery)
            ->where('status', 'Approved')
            ->join('campaigns', 'leads.campaign_name', '=', 'campaigns.name')
            ->sum('campaigns.payout_amount');

        $recentLeads = Lead::orderByDesc('created_at')->limit(10)->get();

        // Chart data
        $chartRows = (clone $leadQuery)
            ->selectRaw('DATE(leads.created_at) as day, COUNT(*) as count, status')
            ->groupBy('status', 'day')
            ->get();
        $chartData = [];
        $allDays = [];
        if ($chartRows) {
            foreach ($chartRows as $row) {
                $chartData[$row->status][$row->day] = $row->count;
                $allDays[$row->day] = true;
            }
        }
        $days = array_keys($allDays);
        sort($days);

        // Top agents
        $top_agents = Lead::select('agent_name')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('agent_name')
            ->orderByDesc('total')
            ->limit(3)
            ->get();
        $top_verifier = Lead::whereNotNull('verifier_name')
            ->select('verifier_name')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('verifier_name')
            ->orderByDesc('total')
            ->first();

        return view('agent-dashboard', compact(
            'start_date',
            'end_date',
            'status_filter',
            'campaign_filter',
            'campaigns',
            'totalLeads',
            'totalApproved',
            'totalPending',
            'revenue',
            'recentLeads',
            'chartData',
            'days',
            'top_agents',
            'top_verifier'
        ));
    }
}
