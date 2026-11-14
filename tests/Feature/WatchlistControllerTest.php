<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatchlistControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_media_to_their_watchlist(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/media/movie/550')
            ->post('/watchlist', [
                'media_id' => 550,
                'type' => 'movie',
                'title' => 'Example Movie',
                'description' => 'Example overview',
                'poster_path' => '/example.jpg',
                'user_id' => $otherUser->id,
            ]);

        $response
            ->assertRedirect('/media/movie/550')
            ->assertSessionHas('success', 'Added to watchlist!');

        $this->assertDatabaseHas('watchlist', [
            'user_id' => $user->id,
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
            'description' => 'Example overview',
            'poster_path' => '/example.jpg',
        ]);
        $this->assertDatabaseCount('watchlist', 1);
    }

    public function test_guest_cannot_add_media_to_a_watchlist(): void
    {
        $response = $this->post('/watchlist', [
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseCount('watchlist', 0);
    }

    public function test_invalid_media_type_does_not_add_a_watchlist_entry(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/watchlist', [
            'media_id' => 550,
            'type' => 'person',
            'title' => 'Example Movie',
        ]);

        $response->assertSessionHasErrors('type');
        $this->assertDatabaseCount('watchlist', 0);
    }
}
