<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Did;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\SystemNote;
use App\Notifications\NewLeadSubmitted;

class AgentLeadController extends Controller
{
    public function create(Request $request)
    {
        $dids = Did::orderBy('did_number')->get();
        $campaigns = Campaign::orderBy('name')->get();
        $note = SystemNote::find(1)?->content;
        $agent_name = Auth::user()->username;
        $verifier_name = $agent_name;
        return view('agent.leads.create', compact('dids', 'campaigns', 'note', 'agent_name', 'verifier_name'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'email' => 'nullable|email',
            'agent_name' => 'required|string',
            'verifier_name' => 'nullable|string',
            'notes' => 'nullable|string',
            'did_number' => 'required|string',
            'campaign_name' => 'nullable|string',
        ]);

        $agent = User::where('role', 'agent')
            ->whereRaw('LOWER(username) = ?', [strtolower($validated['agent_name'])])
            ->first();
        $agent_name = $agent ? $agent->username : Auth::user()->username;
        $verifier_name = $validated['verifier_name'] ?? $agent_name;

        $lead = Lead::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'email' => $validated['email'],
            'notes' => $validated['notes'],
            'status' => 'Pending',
            'agent_name' => $agent_name,
            'verifier_name' => $verifier_name,
            'did_number' => $validated['did_number'],
            'campaign_name' => $validated['campaign_name'],
        ]);

        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewLeadSubmitted($lead));

        return redirect()->route('agent.leads.create')->with('success', 'Lead added successfully!');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $campaign_filter = $request->input('campaign', '');

        $leadsQuery = Lead::where('agent_name', $user->username)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        if ($campaign_filter) {
            $leadsQuery->where('campaign_name', $campaign_filter);
        }
        $leads = $leadsQuery->orderByDesc('created_at')->get();

        $campaigns = Lead::where('agent_name', $user->username)
            ->select('campaign_name')
            ->distinct()
            ->get();

        return view('agent.leads.index', compact('leads', 'campaigns', 'start_date', 'end_date', 'campaign_filter'));
    }

    public function autocompleteAgents(Request $request)
    {
        $term = strtolower($request->input('term', ''));
        $agents = \App\Models\User::where('role', 'agent')->pluck('username');
        $results = $agents->filter(function ($username) use ($term) {
            return stripos($username, $term) === 0;
        })->values();
        return response()->json($results);
    }
}
