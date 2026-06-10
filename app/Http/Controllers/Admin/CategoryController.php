<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('posts');

        // Search
        if ($search = $request->query('search')) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        // Sorting
        $sort = $request->query('sort', 'az');
        
        switch ($sort) {
            case 'za':
                $query->orderBy('nama_kategori', 'desc');
                break;
            case 'most':
                $query->orderBy('posts_count', 'desc');
                break;
            case 'least':
                $query->orderBy('posts_count', 'asc');
                break;
            default: // az
                $query->orderBy('nama_kategori', 'asc');
                break;
        }

        $categories = $query->paginate(15)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }
}
