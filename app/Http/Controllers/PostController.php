<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Posts/Index', ['posts' => Post::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->content;
        $post->author = auth()->id();
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');

    }
}
