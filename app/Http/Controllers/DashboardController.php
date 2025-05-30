<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();
        if ($user instanceof User && method_exists($user, 'hasRole')) {
            if ($user->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('agent')) {
                return redirect('/agent/dashboard');
            } elseif ($user->hasRole('verifier')) {
                return redirect('/verifier/dashboard');
            } elseif ($user->hasRole('manager')) {
                return redirect('/manager-dashboard');
            } elseif ($user->hasRole('sales')) {
                return redirect('/sales-dashboard');
            }
        }
        abort(403, 'Unauthorized');
    }
}
