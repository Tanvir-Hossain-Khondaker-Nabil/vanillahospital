<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database {name} {type?} {collation?} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates a Database';

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
        try {
            $name = $this->argument('name');

            $collation = $this->argument('collation');

            switch ($collation) {
                case 'utf8':
                    $cot = "CHARACTER SET utf8 COLLATE utf8_general_ci";
                    break;
                case 'utf8-unicode':
                    $cot = "CHARACTER SET utf8 COLLATE utf8_unicode_ci";
                    break;
                case 'utf8mb4':
                    $cot = "CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
                    break;
                case 'utf8mb4-unicode':
                    $cot = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                    break;

                default:
                    $cot = "CHARACTER SET utf8 COLLATE utf8_general_ci";
                    break;
            }


            $crearbd = DB::connection('mysql')->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "."'".$name."'");

            if(empty($crearbd)) {
                DB::connection('mysql')->select('CREATE DATABASE '. $name .' '.$cot);
                $this->info("Database '$name' of type mysql with collation '$cot' has been successfully created! ");
            }
            else {
                $this->info("Database with name '$name' already exists !");
            }

        }

        catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }

}
