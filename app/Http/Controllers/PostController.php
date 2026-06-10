<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $carousel = Post::with(['category', 'categories'])->whereNotNull('published_at')->orderBy('published_at', 'desc')->take(5)->get();

        $query = Post::with(['category', 'categories'])->whereNotNull('published_at');

        // Search
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categoryId = $request->query('category')) {
            $query->where(function($q) use ($categoryId) {
                $q->where('category_id', $categoryId)
                  ->orWhereHas('categories', function($q) use ($categoryId) {
                      $q->where('categories.id', $categoryId);
                  });
            });
        }

        // Sorting
        $sort = $request->query('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'az':
                $query->orderBy('title', 'asc');
                break;
            case 'za':
                $query->orderBy('title', 'desc');
                break;
            default: // latest
                $query->orderBy('published_at', 'desc');
                break;
        }

        $posts = $query->paginate(6)->withQueryString();

        $popular = Post::with(['category', 'categories'])->whereNotNull('published_at')->orderBy('published_at', 'desc')->take(5)->get();

        $categories = Category::withCount('posts')->get();

        return view('public.index', compact('carousel', 'posts', 'popular', 'categories'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with(['category', 'categories'])->firstOrFail();
        $related = Post::where('category_id', $post->category_id)->where('id', '!=', $post->id)->take(4)->get();

        return view('public.show', compact('post', 'related'));
    }
}
