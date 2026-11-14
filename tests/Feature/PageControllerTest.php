<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_saved_media_renders_the_watchlist_button_as_selected(): void
    {
        $user = User::factory()->create();
        $user->watchMedia()->create([
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
            'description' => 'Example overview',
        ]);

        $response = $this->actingAs($user)->get('/media/movie/550');

        $response
            ->assertSee('class="watchlist"', false)
            ->assertSee('const isInWatchlist = true;', false)
            ->assertSee('watchlist.classList.toggle("is-selected", isInWatchlist);', false);
    }

    public function test_media_saved_by_another_user_does_not_render_as_selected(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();
        $owner->watchMedia()->create([
            'media_id' => 550,
            'type' => 'movie',
            'title' => 'Example Movie',
            'description' => 'Example overview',
        ]);

        $response = $this->actingAs($viewer)->get('/media/movie/550');

        $response
            ->assertSee('class="watchlist"', false)
            ->assertSee('const isInWatchlist = false;', false)
            ->assertSee('watchlist.classList.toggle("is-selected", isInWatchlist);', false);
    }

    public function test_same_id_saved_as_tv_does_not_select_movie_button(): void
    {
        $user = User::factory()->create();
        $user->watchMedia()->create([
            'media_id' => 550,
            'type' => 'tv',
            'title' => 'Example TV show',
            'description' => 'Example overview',
        ]);

        $response = $this->actingAs($user)->get('/media/movie/550');

        $response
            ->assertSee('class="watchlist"', false)
            ->assertSee('const isInWatchlist = false;', false)
            ->assertSee('watchlist.classList.toggle("is-selected", isInWatchlist);', false);
    }
}
