<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List users';

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
        $email = $this->anticipate('What is your email ?', ['admin@admin.com']);
        $password = $this->secret('Type your password');
        $isAuth = false;

        $authUser = User::query()->where('email', $email)->first();
        if (! $authUser || ! password_verify($password, $authUser->password)) {
            $this->error('Username or password incorrect');
        } else {
            $this->info('You\'re now connected');
            $isAuth = true;
        }

        if ($isAuth) {
            $users = User::query()->where('email', '!=', $email);

            if ($users->count()) {
                $array_columns = ['id', 'name', 'email', 'phone'];
                $this->table(
                    $array_columns,
                    $users->get($array_columns)->toArray()
                );
            } else {
                $this->comment('There is no user in database');
            }
        }
    }
}
