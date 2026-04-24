<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->where('status', 1);

        if ($request->filled('q')) {
            $keyword = trim($request->q);

            $posts->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('summary', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }

        $posts = $posts->latest('id')
            ->paginate(6)
            ->withQueryString();

        $latestPosts = Post::query()
            ->where('status', 1)
            ->latest('id')
            ->take(5)
            ->get();

        return view('news.index', compact('posts', 'latestPosts'));
    }

    public function show($slug)
    {
        $post = Post::query()
            ->with([
                'comments' => function ($query) {
                    $query->where('is_visible', true)
                        ->with('user')
                        ->latest();
                },
            ])
            ->where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::query()
            ->where('status', 1)
            ->where('id', '!=', $post->id)
            ->latest('id')
            ->take(3)
            ->get();

        $latestPosts = Post::query()
            ->where('status', 1)
            ->where('id', '!=', $post->id)
            ->latest('id')
            ->take(5)
            ->get();

        return view('news.show', compact('post', 'relatedPosts', 'latestPosts'));
    }
}