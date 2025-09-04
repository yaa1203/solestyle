<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products'); // Menambahkan withCount untuk menghitung produk

        // Filter status kalau ada
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Cari berdasarkan nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    /**
     * Form tambah kategori
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:categories,name',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create($validated);

        return redirect()->route('category.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail kategori
     */
    public function show(Category $category)
    {
        // Load produk yang terkait dengan kategori ini
        $category->load('products');
        
        return view('admin.category.show', compact('category'));
    }

    /**
     * Form edit kategori
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:categories,name,' . $category->id,
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($validated);

        return redirect()->route('category.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Category $category)
    {
        // Cek apakah kategori memiliki produk
        if ($category->products()->count() > 0) {
            return redirect()->route('category.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk!');
        }

        $category->delete();

        return redirect()->route('category.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Toggle status kategori
     */
    public function toggleStatus(Category $category)
    {
        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        $category->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'aktif' : 'nonaktif';

        return redirect()->back()
            ->with('success', "Status kategori berhasil diubah menjadi {$statusText}!");
    }
}