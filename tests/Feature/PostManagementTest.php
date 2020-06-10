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
    public function a_list_can_be_retrieved()
    {
      $this->withoutExceptionHandling();

      factory(Post::class, 3)->create(); //Testing data

      $response = $this->get('/posts'); //Requesting the route
      $response->assertOk();
    }

  /** @test */
  public function a_post_can_be_created()
  {
    $this->withoutExceptionHandling();
    $response = $this->post('/posts', [
      'title' => 'Test',
      'content' => 'Test content'
    ]);

    $response->assertOk();
    $this->assertCount(1, Post::all());
    $post = Post::first();
    $this->assertEquals($post->title, 'Test');
    $this->assertEquals($post->content, 'Test content');
  }
}
