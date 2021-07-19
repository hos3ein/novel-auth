<?php

namespace Hos3ein\NovelAuth\Tests;

use App\Models\User;
use Hos3ein\NovelAuth\Features\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SampleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_start()
    {
        $response = $this->json('post', '/auth');
        $response->assertStatus(422);

        $response = $this->json('post', '/auth', ['password' => '-']);
        $response->assertStatus(422);
    }

    public function test_input_validation()
    {
        $inputs = ['abc@dc', '~sd', '++5'];
        foreach ($inputs as $input) {
            $response = $this->post('/auth', ['email_phone' => $input], ['Accept' => 'application/json']);
            $response->assertStatus(422);
//        $response->assertSessionHasErrors(['email']);
        }
    }

    public function test_disable_login()
    {
        app('config')->set(Constants::$configLoginOptions, null);
        $user = User::factory()->create();
        $response = $this->json('post', '/auth', ['email_phone' => $user->email]);
        $response->assertStatus(422);
        $response->assertSeeText('Login is disabled');
    }

    public function test_disable_user()
    {
        $user = User::factory()->create(['active'=>0]);
        $response = $this->json('post', '/auth', ['email_phone' => $user->email]);
        $response->assertStatus(422);
        $response->assertSeeText('You are disabled');
    }

    public function test_disable_register()
    {
        app('config')->set(Constants::$configRegisterMethods, []);
        $response = $this->json('post', '/auth', ['email_phone' => 'aaa@bbb.ccc']);
        $response->assertStatus(422);
        $response->assertSeeText('Not found and also register is disabled');
    }

    public function test_only_email_or_phone()
    {
        app('config')->set(Constants::$configRegisterMethods, [Constants::$EMAIL_MODE]);
        $response = $this->json('post', '/auth', ['email_phone' => '09123456789']);
        $response->assertStatus(422);
        $response->assertSeeText('Please SignUp with email');

        app('config')->set(Constants::$configRegisterMethods, [Constants::$PHONE_MODE]);
        $response = $this->json('post', '/auth', ['email_phone' => 'aaa@bbb.ccc']);
        $response->assertStatus(422);
        $response->assertSeeText('Please SignUp with phone');
    }
}
