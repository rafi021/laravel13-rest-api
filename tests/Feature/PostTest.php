<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
});

describe('Post Management', function () {

    it('displays the posts index page', function () {
        Post::factory(3)->create(['category_id' => $this->category->id]);

        $this->actingAs($this->user)
            ->get(route('posts.index'))
            ->assertSuccessful()
            ->assertSee('Posts');
    });

    it('displays the create post form', function () {
        $this->actingAs($this->user)
            ->get(route('posts.create'))
            ->assertSuccessful()
            ->assertSee('Create Post');
    });

    it('stores a new post', function () {
        $this->actingAs($this->user)
            ->post(route('posts.store'), [
                'category_id' => $this->category->id,
                'title' => 'My First Post',
                'content' => 'Some content here.',
            ])
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', ['title' => 'My First Post']);
    });

    it('validates required fields when storing a post', function (string $field) {
        $data = [
            'category_id' => $this->category->id,
            'title' => 'Test Post',
            'content' => 'Content',
        ];

        $data[$field] = '';

        $this->actingAs($this->user)
            ->post(route('posts.store'), $data)
            ->assertSessionHasErrors($field);
    })->with(['category_id', 'title', 'content']);

    it('validates category exists when storing', function () {
        $this->actingAs($this->user)
            ->post(route('posts.store'), [
                'category_id' => 9999,
                'title' => 'Test',
                'content' => 'Content',
            ])
            ->assertSessionHasErrors('category_id');
    });

    it('displays the edit post form', function () {
        $post = Post::factory()->create(['category_id' => $this->category->id, 'title' => 'Edit Me']);

        $this->actingAs($this->user)
            ->get(route('posts.edit', $post))
            ->assertSuccessful()
            ->assertSee('Edit Me');
    });

    it('updates an existing post', function () {
        $post = Post::factory()->create(['category_id' => $this->category->id]);

        $this->actingAs($this->user)
            ->put(route('posts.update', $post), [
                'category_id' => $this->category->id,
                'title' => 'Updated Title',
                'content' => 'Updated content.',
            ])
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    });

    it('deletes a post', function () {
        $post = Post::factory()->create(['category_id' => $this->category->id]);

        $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    });

    it('requires authentication to access posts', function () {
        $this->get(route('posts.index'))
            ->assertRedirect(route('login'));
    });
});
