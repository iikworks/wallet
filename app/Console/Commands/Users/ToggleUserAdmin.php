<?php

namespace App\Console\Commands\Users;

use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ToggleUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:toggle-admin {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle is user admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $userId = $this->argument('user_id');
        $user = User::query()->find($userId);
        if (!$user) {
            $this->error("User with ID $userId not found");
            return CommandAlias::FAILURE;
        }

        if (!$user->is_admin) {
            $user->is_admin = true;
            $user->save();

            $this->info("User with ID $userId is now administrator");
        } else {
            $user->is_admin = false;
            $user->save();
            
            $this->info("User with ID $userId is no longer an administrator");
        }
        return CommandAlias::SUCCESS;
    }
}
