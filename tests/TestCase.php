<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

abstract class TestCase extends BaseTestCase
{
    public function createPassportClients()
    {
        $url = config('app.url');
        $clientRepository = new ClientRepository();
        $clientRepository->createPasswordGrantClient(null, 'Test Password Grant Client', $url);
        $clientRepository->createPersonalAccessClient(null, 'Test Personal Access Client', $url);
    }

    public function loginByFaker($guard = 'api')
    {
        $user = User::factory()->create();
        $this->actingAs($user, $guard);
        $this->createPassportClients();
    }

    public function requestToken($user, $password = null)
    {
        $client = Client::where('password_client', true)->first();

        return $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => $password ?? '123123',
            'scope' => ''
        ]);
    }
}
