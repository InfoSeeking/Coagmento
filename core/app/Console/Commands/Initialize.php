<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Validator;

class Initialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up initial temporary administrative user.';

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
        User::create([
            'name' => 'Administrator',
            'email' => 'e@e.com',
            'password' => bcrypt(' '),
            'is_admin' => true,
        ]);

        $this->comment('Temporary admin user created!');
    }
}
