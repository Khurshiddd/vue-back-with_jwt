<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return auth()->user()->posts;
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $user_id = Auth::id();
        $post = new Post();
        $post->user_id = $user_id;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->body = $request->body;

        $post->save();
        return response()->json([
            'succes'=>true,
            'message'=>'post yaratildi'
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $user_id = Auth::id();
        if($user_id==$post->user_id){
            $post->title = $request->title;
            $post->description = $request->description;
            $post->body = $request->body;

            $post->save();
            return response()->json([
                'succes'=>true,
                'message'=>'post o\'zgartirildi'
            ],201);
        }
        else{
            return response()->json([
                'succes'=>false,
                'message'=>'post o\'zgartirilmadi'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $user_id = Auth::id();
        if($user_id==$post->user_id){
            $post->delete();
            return response()->json([
                'succes'=>true,
                'message'=>'post o\'chirldi'
            ]);
        }else{
            return response()->json([
                'succes'=>false,
                'message'=>'post o\'chirlmadi'
            ]);
        }
    }
}
