<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    public function index(Request $request)
    {
        $posts = $this->postService->getAllPosts($request->per_page ?? 10);
        return PostResource::collection($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $post = $this->postService->createPost($validated);
        return new PostResource($post);
    }

    public function show(Post $post)
    {
        return new PostResource($post->load(['createdBy', 'tags', 'images']));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $post = $this->postService->updatePost($post, $validated);
        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);
        return response()->noContent();
    }
} 