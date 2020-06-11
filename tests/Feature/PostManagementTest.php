<?php

namespace Tests\Feature;

use App\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostManagementTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function a_list_of_posts_can_be_retrieved()
  {
    $this->withoutExceptionHandling();

    factory(Post::class, 3)->create(); //Testing data

    $response = $this->get('/posts'); //Requesting the route
    $response->assertOk();

    $posts = Post::all();

    $response->assertViewIs('posts.index');
    $response->assertViewHas('posts', $posts);

  }

  /** @test */
  public function a_post_can_be_retrieved()
  {
    $this->withoutExceptionHandling();

    $post = factory(Post::class)->create(); //Testing data

    $response = $this->get('/posts/' . $post->id); //Requesting the route
    
    $response->assertOk();

    $post = Post::first();

    $response->assertViewIs('posts.show');
    $response->assertViewHas('post', $post);
  }

  /** @test */
  public function a_post_can_be_created()
  {
    $this->withoutExceptionHandling();
    $response = $this->post('/posts', [
      'title' => 'Test',
      'content' => 'Test content'
    ]);
    
    $this->assertCount(1, Post::all());
    $post = Post::first();

    $this->assertEquals($post->title, 'Test');
    $this->assertEquals($post->content, 'Test content');

    $response->assertRedirect('/posts/' . $post->id);
  }

  /** @test */
  public function post_title_is_required()
  {
    $response = $this->post('/posts', [
      'title' => '',
      'content' => ''
    ]);
    $response->assertSessionHasErrors(['title']);
  }

  /** @test */
  public function post_content_is_required()
  {
    $response = $this->post('/posts', [
      'title' => 'Title',
      'content' => ''
    ]);
    $response->assertSessionHasErrors(['content']);
  }

  /** @test */
  public function a_post_can_be_updated()
  {
    $this->withoutExceptionHandling();
    $post = factory(Post::class)->create(); //Testing data

    $response = $this->put('/posts/' . $post->id, [
      'title' => 'Test',
      'content' => 'Test content'
    ]);
    
    $this->assertCount(1, Post::all());
    $post = $post->fresh();

    $this->assertEquals($post->title, 'Test');
    $this->assertEquals($post->content, 'Test content');

    $response->assertRedirect('/posts/' . $post->id);
  }

  /** @test */
  public function a_post_can_be_deleted()
  {
    $this->withoutExceptionHandling();
    $post = factory(Post::class)->create(); //Testing data

    $response = $this->delete('/posts/' . $post->id);
    
    $this->assertCount(0, Post::all());

    $response->assertRedirect('/posts/');
  }
}
