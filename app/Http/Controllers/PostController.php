<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Contracts\DataTable;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Posts/Index', ['posts' => Post::latest()->get()]);
    }

    public function data(Request $request){
        $posts = Post::with('user');

        return DataTables::eloquent($posts)
            ->addColumn('created_by', function($post){
                return $post->user->name;
            })
            ->addColumn('actions', function($post){
                return '';
            })
            ->rawColumns(['actions'])
            ->make(true);
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
