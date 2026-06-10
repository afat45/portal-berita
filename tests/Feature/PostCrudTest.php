<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_perform_crud_on_posts()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $category = Category::factory()->create();

        // create
        $response = $this->post(route('admin.posts.store'), [
            'title' => 'Test Post',
            'category_id' => $category->id,
            'content' => 'Conten test',
            'author' => 'Admin',
        ]);
        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);

        $post = Post::first();

        // edit
        $response = $this->put(route('admin.posts.update', $post), [
            'title' => 'Updated Post',
            'category_id' => $category->id,
            'content' => 'Updated content',
            'author' => 'Admin',
        ]);
        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Updated Post']);

        // delete
        $response = $this->delete(route('admin.posts.destroy', $post));
        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
