<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::hasUser())
        {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $posts = Post::where('user_id', Auth::user()->getAuthIdentifier())->paginate();
        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        if(!Auth::hasUser())
        {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        if(!$category = Category::where('id', $request->input('category_id'))->first())
        {
            return response()->json(["message" => "Invalid category"], 403);
        }

        $post = new Post();
        $post->category_id = $request->input('category_id');
        $post->user_id = Auth::user()->getAuthIdentifier();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if(!Auth::hasUser())
        {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        return response()->json(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if(!Auth::hasUser())
        {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $post->category_id = $request->input('category_id');
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return response()->json(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(!Auth::hasUser()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
