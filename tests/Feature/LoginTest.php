<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_user_can_log_in_with_their_email(): void
    {
        $user = User::factory()->create([
            'name' => 'MovieFan',
            'email' => 'moviefan@example.com',
            'password' => 'correct-password',
        ]);

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'correct-password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_log_in_with_their_name(): void
    {
        $user = User::factory()->create([
            'name' => 'MovieFan',
            'email' => 'moviefan@example.com',
            'password' => 'correct-password',
        ]);

        $response = $this->post('/login', [
            'login' => $user->name,
            'password' => 'correct-password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_return_a_login_error(): void
    {
        User::factory()->create([
            'name' => 'MovieFan',
            'email' => 'moviefan@example.com',
            'password' => 'correct-password',
        ]);

        $response = $this->from('/login')->post('/login', [
            'login' => 'MovieFan',
            'password' => 'incorrect-password',
        ]);

        $response->assertRedirect('/login')->assertSessionHasErrors('login');
        $this->assertGuest();
    }
}
