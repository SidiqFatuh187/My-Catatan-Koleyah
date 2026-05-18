<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $title = 'Kategori';
        $subtitle = 'Daftar Kategori';

        $category = Category::where('user_id', Auth::id())
   //    ->withCount('todos')
        ->latest()
        ->get();

        return view('category.index', compact('title', 'subtitle', 'category'));
    }

    public function create()
    {
        $title = 'Kategori';
        $subtitle = 'Tambah Kategori';

        return view('category.create', compact('title', 'subtitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

            Category::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'color' => $request->color ?? '#3B82F6', // default biru
                'icon' => $request->icon,
            ]);

            return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
        }

    public function edit($id)
    {
        $title = 'Kategori';
        $subtitle = 'Edit Kategori';

       $category = Category::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        return view('category.edit', compact('title', 'subtitle', 'category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        $category = Category::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $category->update([
            'name' => $request->name,
            'color' => $request->color ?? '#3B82F6', // default biru
            'icon' => $request->icon,
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

  public function destroy($id)
{
    $category = Category::where('user_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    try {
        $category->delete();
        return redirect()->route('category.index')
            ->with('success', 'Kategori berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menghapus kategori, coba lagi.');
    }
}
}
