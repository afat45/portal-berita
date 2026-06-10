<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['category', 'categories']);

        // Search
        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
        }

        // Sorting
        $sort = $request->query('sort', 'latest');
        
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'az':
                $query->orderBy('title', 'asc');
                break;
            case 'za':
                $query->orderBy('title', 'desc');
                break;
            default: // latest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'additional_categories' => 'nullable|array',
            'additional_categories.*' => 'exists:categories,id',
            'content' => 'required',
            'author' => 'required|string|max:100',
            'image_type' => 'required|in:file,url',
            'image_file' => 'nullable|image|max:5120',
            'image_url' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);

        // Handle image based on type
        if ($request->input('image_type') == 'file' && $request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('images', 'public');
        } elseif ($request->input('image_type') == 'url' && $request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        // Remove image_type and image fields from data
        unset($data['image_type'], $data['image_file'], $data['image_url']);

        $data['slug'] = Str::slug($data['title']) . '-' . time();

        // Store additional categories separately
        $additionalCategories = $data['additional_categories'] ?? [];
        unset($data['additional_categories']);

        $post = Post::create($data);

        // Sync categories (main + additional)
        $allCategories = collect([$data['category_id']])->merge($additionalCategories)->unique()->toArray();
        $post->categories()->sync($allCategories);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel game berhasil dibuat.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'additional_categories' => 'nullable|array',
            'additional_categories.*' => 'exists:categories,id',
            'content' => 'required',
            'author' => 'required|string|max:100',
            'image_type' => 'required|in:file,url',
            'image_file' => 'nullable|image|max:5120',
            'image_url' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);

        // Handle image based on type
        if ($request->input('image_type') == 'file' && $request->hasFile('image_file')) {
            // Delete old image if it's local file
            if ($post->image && str_starts_with($post->image, 'images/')) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image_file')->store('images', 'public');
        } elseif ($request->input('image_type') == 'url' && $request->filled('image_url')) {
            // Delete old local image if switching to URL
            if ($post->image && str_starts_with($post->image, 'images/')) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->input('image_url');
        }

        // Store additional categories separately
        $additionalCategories = $data['additional_categories'] ?? [];
        
        // Remove image_type and image fields from data
        unset($data['image_type'], $data['image_file'], $data['image_url'], $data['additional_categories']);

        $post->update($data);

        // Sync categories (main + additional)
        $allCategories = collect([$data['category_id']])->merge($additionalCategories)->unique()->toArray();
        $post->categories()->sync($allCategories);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel game berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Berita dihapus.');
    }
}
