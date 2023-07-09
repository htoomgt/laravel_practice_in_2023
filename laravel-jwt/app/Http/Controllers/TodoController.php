<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify:api');
    }

    public function index()
    {
        $todos = Todo::all();
        return response()->json([
            'status' => 'success',
            'todos' => $todos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Todo created  successfully',
            'todo' => $todo
        ], 201);
    }

    public function show($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'todo' => $todo
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string|max:255'
        ]);

        $todo = Todo::find($id);
        $todo->update($request->all());
        $todo->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo updated successfully',
            'todo' => $todo
        ]);
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo not found'
            ], 404);
        } else {
            $todo->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Todo deleted successfully'
            ]);
        }
    }
}
