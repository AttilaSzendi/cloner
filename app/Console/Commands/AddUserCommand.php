<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AddUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->ask('Name of the user:');
        $email = $this->askEmail();

        if(User::query()->where('email', $email)->exists()) {
            $email = $this->askEmail(true);
        }

        [$password, $passwordAgain] = $this->askPassword();

        if($password !== $passwordAgain) {
            [$password] = $this->askPassword(true);
        }

        if ($this->confirm("Name: $name, email: $email. Do you wish to create the user?")) {
            User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
            ]);

            $this->info('The User has been created!');
        }
    }

    protected function askPassword($retry = false): array
    {
        if($retry) {
            $this->error('The passwords are not matching, please retry!');
        }

        $password = $this->secret('Password of the user:');
        $passwordAgain = $this->secret('Password again:');

        return [$password, $passwordAgain];
    }

    protected function askEmail($retry = false)
    {
        if($retry) {
            $this->error('The email has already in use, please provide a different one!');
        }

        return $this->ask('Email of the user:');
    }
}
