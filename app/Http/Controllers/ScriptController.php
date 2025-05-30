<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\SystemNote;

class ScriptController extends Controller
{
    public function show()
    {
        // For now, use SystemNote as the script source (id=1)
        $script = SystemNote::find(1);
        return view('agent.script', [
            'script' => $script ? $script->content : 'No script available.'
        ]);
    }
}
