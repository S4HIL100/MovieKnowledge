<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatchlistAjaxTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_media_with_a_json_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/watchlist', [
                'media_id' => 550,
                'type' => 'movie',
                'title' => 'Example Movie',
            ]);

        $response
            ->assertCreated()
            ->assertJsonStructure(['id', 'message'])
            ->assertJson(['message' => 'Added to watchlist.']);

        $this->assertDatabaseHas('watchlist', [
            'user_id' => $user->id,
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
        ]);
    }

    public function test_invalid_json_submission_returns_validation_errors_without_saving(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/watchlist', [
            'media_id' => 550,
            'type' => 'person',
            'title' => 'Example Movie',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('type');
        $this->assertDatabaseCount('watchlist', 0);
    }

    public function test_authenticated_user_can_remove_their_watchlist_entry(): void
    {
        $user = User::factory()->create();
        $watchlist = $user->watchMedia()->create([
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
        ]);

        $response = $this->actingAs($user)->deleteJson("/watchlist/{$watchlist->id}");

        $response->assertOk()->assertJson(['message' => 'Removed from watchlist.']);
        $this->assertDatabaseMissing('watchlist', ['id' => $watchlist->id]);
    }

    public function test_user_cannot_remove_another_users_watchlist_entry(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $watchlist = $owner->watchMedia()->create([
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
        ]);

        $response = $this->actingAs($otherUser)->deleteJson("/watchlist/{$watchlist->id}");

        $response->assertNotFound();
        $this->assertDatabaseHas('watchlist', ['id' => $watchlist->id]);
    }

    public function test_media_page_shows_when_media_is_already_in_watchlist(): void
    {
        $user = User::factory()->create();
        $watchlist = $user->watchMedia()->create([
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
        ]);

        $response = $this->actingAs($user)->get('/media/movie/550');

        $response
            ->assertViewHas('isInWatchlist', true)
            ->assertViewHas('watchlistId', $watchlist->id);
    }
}
