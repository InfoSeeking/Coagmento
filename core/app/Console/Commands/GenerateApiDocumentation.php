<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateApiDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:apidoc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-generate API documentation to public/apidoc';

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
        //
        $status = 0;
        $output = [];
        exec('apidoc --help', $output, $status);
        if ($status == 1) {
            printf("apidoc does not seem to be installed (returned exit status 1)\n");
            printf("Install apidoc globally with \"npm install -g apidoc\"\n");
            printf("See http://apidocjs.com/ for further instructions\n");
            return;
        }

        exec('apidoc -i ./app/Http/Controllers/Api -o ./public/apidoc', $output, $status);
        if ($status == 1) {
            printf("apidoc returned exit status 1)\n");
            printf(implode($output, "\n"));
        }

        printf("Documentation generated in public/apidoc\n");
    }
}
