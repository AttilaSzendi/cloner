<?php

namespace Tests\Integration;

use App\Jobs\CloneRepository;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AddUserCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_be_created()
    {
        $name = 'Taylor Otwell';
        $email = 'taylor@laravel.com';

        $this->artisan('user:add')
            ->expectsQuestion('Name of the user:', $name)
            ->expectsQuestion('Email of the user:', $email)
            ->expectsQuestion('Password of the user:', 'secret')
            ->expectsQuestion('Password again:', 'secret')
            ->expectsQuestion("Name: $name, email: $email. Do you wish to create the user?", 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_retry_email_if_it_is_already_in_use()
    {
        $name = 'Taylor Otwell';
        $email = 'taylor@laravel.com';
        $otherEmail = 'taylor2@laravel.com';

        User::factory()->create(['email' => $email]);

        $this->artisan('user:add')
            ->expectsQuestion('Name of the user:', $name)
            ->expectsQuestion('Email of the user:', $email)
            ->expectsOutput('The email has already in use, please provide a different one!')
            ->expectsQuestion('Email of the user:', $otherEmail)
            ->expectsQuestion('Password of the user:', 'secret')
            ->expectsQuestion('Password again:', 'secret')
            ->expectsQuestion("Name: $name, email: $otherEmail. Do you wish to create the user?", 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $otherEmail,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_retry_password_if_confirmation_is_not_matching()
    {
        $name = 'Taylor Otwell';
        $email = 'taylor@laravel.com';
        $password = 'secret';
        $passwordWithMistake = 'notsecret';

        $this->artisan('user:add')
            ->expectsQuestion('Name of the user:', $name)
            ->expectsQuestion('Email of the user:', $email)
            ->expectsQuestion('Password of the user:', $password)
            ->expectsQuestion('Password again:', $passwordWithMistake)
            ->expectsOutput('The passwords are not matching, please retry!')
            ->expectsQuestion('Password of the user:', $password)
            ->expectsQuestion('Password again:', $password)
            ->expectsQuestion("Name: $name, email: $email. Do you wish to create the user?", 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }
}
