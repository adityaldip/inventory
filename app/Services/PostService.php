<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    public function getAllPosts(int $perPage = 10): LengthAwarePaginator
    {
        return Post::with(['createdBy', 'tags', 'images'])
            ->latest()
            ->paginate($perPage);
    }

    public function createPost(array $data): Post
    {
        // Get the first user as default
        $defaultUser = User::first();
        if (!$defaultUser) {
            throw new \Exception('No default user found. Please run UserSeeder first.');
        }

        $data['created_by_id'] = $defaultUser->id;
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        
        $post = Post::create($data);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return $post->load(['createdBy', 'tags', 'images']);
    }

    public function updatePost(Post $post, array $data): Post
    {
        if (isset($data['title'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $post->id);
        }

        $post->update($data);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return $post->load(['createdBy', 'tags', 'images']);
    }

    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }

    public function findPost(string $id): ?Post
    {
        return Post::with(['createdBy', 'tags', 'images'])->find($id);
    }

    private function generateUniqueSlug(string $title, ?string $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = Post::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = Post::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
} 