<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_post_with_tags()
    {
        $tag = Tag::factory()->create(['name' => 'technology']);
        
        $response = $this->postJson('/api/v1/posts', [
            'title' => 'Test Post',
            'content' => 'Test Content',
            'tags' => [$tag->id]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'slug',
                    'tags'
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'Test Content'
        ]);
    }

    public function test_can_upload_image_to_post()
    {
        $post = Post::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson('/api/v1/images', [
            'image' => $file,
            'type' => 'post',
            'id' => $post->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('images', [
            'imageable_type' => Post::class,
            'imageable_id' => $post->id
        ]);
    }
} 