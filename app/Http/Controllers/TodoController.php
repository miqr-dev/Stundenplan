<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate(['text' => 'required|string']);
        auth()->user()->todos()->create($validated);
        return redirect()->back();
    }

public function update(Request $request, Todo $todo)
{
    $validated = $request->validate([
        'text' => 'sometimes|required|string',
        'is_done' => 'sometimes|required|boolean',
    ]);
    
    $todo->update($validated);
    
    return redirect()->back()->withViewData(['preserveState' => true]);
}

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->back();
    }
}
