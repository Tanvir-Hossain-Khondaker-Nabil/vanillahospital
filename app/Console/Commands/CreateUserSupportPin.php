<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUserSupportPin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user_support_pin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New User Support Pin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::select('id', 'full_name')->get();

        foreach($users as $user)
        {
            $user->support_pin = generate_pin();
            $user->save();
        }
    }
}
