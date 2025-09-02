<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Dotenv\Dotenv;

class PrepareTestDatabase extends Command
{
    protected $signature = 'test:prepare {--fresh : Drop all tables and re-run all migrations} {--seed : Seed the database after migration}';
    protected $description = 'Prepare database for running tests';

    public function handle()
    {
        $this->info('Preparing test database...');

        $_ENV = [];
        $_SERVER['APP_ENV'] = 'testing';

        $dotenv = Dotenv::createImmutable(base_path(), '.env.testing');
        $dotenv->load();

       
        $this->laravel->environment('testing');

        Config::set('database.connections.mysql.database', env('DB_DATABASE', 'access_manager_test'));
        Config::set('database.connections.mysql.username', env('DB_USERNAME', 'access_user'));
        Config::set('database.connections.mysql.password', env('DB_PASSWORD', 'secure_password'));
        
        DB::purge('mysql');
        DB::reconnect('mysql');

        $this->info('Current environment: ' . app()->environment());
        $this->info('Current database: ' . DB::connection()->getDatabaseName());
        $this->info('Database config: ' . json_encode(Config::get('database.connections.mysql')));

        $options = [
            '--env' => 'testing',
            '--database' => 'mysql',
            '--force' => true,
        ];

        if ($this->option('fresh')) {
            Artisan::call('migrate:fresh', $options);
            $this->info(Artisan::output());
        } else {
            Artisan::call('migrate', $options);
            $this->info(Artisan::output());
        }

        if ($this->option('seed')) {
            Artisan::call('db:seed', array_merge($options, ['--class' => 'DatabaseSeeder']));
            $this->info(Artisan::output());
        }

        $this->info('Test database is ready!');
    }
}