<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use App\Models\Category;
class TodoController extends Controller
{
    public function index()
    {
        $title = 'My Tasks';
        $subtitle = 'Daftar Tasks';

        $todos = Todo::where('user_id', Auth::id())
        ->with('category')
        ->when(request('status'), fn($q) => $q->where('status', request('status')))
        ->latest()
        ->get();

        $category = category::where('user_id', Auth::id())->get();

        return view('todo.index', compact('title', 'subtitle', 'todos', 'category'));
    }

    public function create()
    {
        $title = 'My Tasks';
        $subtitle = 'Tambah Tasks';

        $category = category::where('user_id', Auth::id())->get();

        return view('todo.create', compact('title', 'subtitle', 'category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:category,id',
            'priority'    => 'required|in:low,medium,high',
            'deadline'    => 'nullable|date|after_or_equal:today',
        ], [
            'title.required'        => 'Judul task wajib diisi.',
            'priority.required'     => 'Priority wajib dipilih.',
            'priority.in'           => 'Priority tidak valid.',
            'deadline.date'         => 'Format deadline tidak valid.',
            'deadline.after_or_equal' => 'Deadline tidak boleh sebelum hari ini.',
        ]);

        Todo::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => 'pending',
            'deadline'    => $request->deadline,
        ]);

        return redirect()->route('todo.index')->with('success', 'Task berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $title = 'My Tasks';
        $subtitle = 'Edit Tasks';

        $todo = Todo::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $category = category::where('user_id', Auth::id())->get();

        return view('todo.edit', compact('title', 'subtitle', 'todo', 'category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:category,id',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:active,completed,pending',
            'deadline'    => 'nullable|date',
        ]);

         $todo = Todo::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        try {
        $todo->update([
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'description'  => $request->description,
            'priority'     => $request->priority,
            'status'       => $request->status,
            'deadline'     => $request->deadline,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        return redirect()->route('todo.index')->with('success', 'Task berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui task, coba lagi.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $todo = Todo::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
          
        try {
        $todo->update([
            'status'       => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal update status.'], 500);
        }
    }

        public function destroy($id)
    {
        $todo = Todo::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        try {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Task berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus task, coba lagi.');
        }
    }
}
