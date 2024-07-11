<?php

use App\Models\User;
use App\Services\AuthService;
use Laravel\Passport\Client;

test('test register', function () {
    $input = User::factory()->make()->toArray();
    $input['password'] = "123123";

    $res = $this->postJson(route('auth.register'), $input);

    $res->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'created_at',
                'updated_at',
            ],
            'success',
            'message',
        ])
        ->assertJson([
            'success' => true,
            'message' => 'Successfully registered user',
        ]);
});

test('test login', function () {
    $this->createPassportClients();
    $user = User::factory()->create();

    $res = $this->postJson(route('auth.login'), [
        'email' => $user->email,
        'password' => '123123',
    ]);

    $res->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'token_type',
                'access_token',
                'expires_at',
            ],
            'success',
            'message',
        ])
        ->assertJson([
            'success' => true,
            'message' => 'Successfully logged in',
        ]);
});

test('test logout', function() {
    $this->createPassportClients();
    $auth = AuthService::login(User::factory()->create());

    $this->withHeaders([
        'Authorization' => $auth['token_type'] . ' ' . $auth['access_token'],
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ]);

    $res = $this->getJson(route('auth.logout'));

    $res->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ])
        ->assertJson([
            'message' => 'Successfully logged out',
        ]);
});

test('test request token', function () {
    $res = $this->requestToken(User::factory()->create());

    $res->assertJsonStructure([
        'token_type', 'expires_in', 'access_token'
    ]);
});

test('test refresh token', function () {
    $client = Client::where('password_client', true)->first();
    $auth = $this->requestToken(User::factory()->create());

    $res = $this->postJson('/oauth/token', [
        'grant_type' => 'refresh_token',
        'client_id' => $client->id,
        'client_secret' => $client->secret,
        'refresh_token' => $auth->json('refresh_token'),
        'scope' => ''
    ]);

    $res->assertJsonStructure([
        'token_type', 'expires_in', 'access_token'
    ]);
});