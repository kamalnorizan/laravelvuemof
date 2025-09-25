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

    public function show(Post $post)
    {
        return Inertia::render('Posts/Show', ['posts' => $post]);
    }

    public function data(Request $request){
        $posts = Post::with('user');

        return DataTables::eloquent($posts)
            ->addColumn('created_by', function($post) {
                return $post->user->name;
            })
            ->addColumn('actions', function($post) {
                return [
                    'id' => $post->id,
                ];
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if($request->id && $request->id != ''){
            $post = Post::find($request->id);
            $msg = 'Post updated successfully.';
        }else{
            $post = new Post;
            $post->author = auth()->id();
            $msg = 'Post created successfully.';
        }

        $post->title = $request->title;
        $post->body = $request->content;
        $post->save();

        return redirect()->route('posts.index')->with('success', $msg);

    }

    public function destroy($id)
    {
        Post::destroy($id);
        $msg = 'Post deleted successfully.';
        return response()->json(['success' => true, 'message' => $msg]);

    }
}
