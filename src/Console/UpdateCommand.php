<?php

namespace Ophim\Core\Console;

use Backpack\Settings\app\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Ophim\Core\Database\Seeders\CatalogsTableSeeder;
use Ophim\Core\Database\Seeders\CategoriesTableSeeder;
use Ophim\Core\Database\Seeders\MenusTableSeeder;
use Ophim\Core\Database\Seeders\PermissionsSeeder;
use Ophim\Core\Database\Seeders\RegionsTableSeeder;
use Ophim\Core\Database\Seeders\SettingsTableSeeder;
use Ophim\Core\Database\Seeders\ThemesTableSeeder;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ophim:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật Ophim';

    protected $progressBar;


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
     * @return int
     */
    public function handle()
    {
        $this->progressBar = $this->output->createProgressBar(6);
        $this->progressBar->minSecondsBetweenRedraws(0);
        $this->progressBar->maxSecondsBetweenRedraws(120);
        $this->progressBar->setRedrawFrequency(1);

        $this->progressBar->start();
        $this->newLine(1);
        $this->info('Publish Config');
        $this->call('vendor:publish', [
            '--provider' => 'Backpack\CRUD\BackpackServiceProvider',
            '--tag' => 'config',
        ]);
        $this->progressBar->advance();

        $this->newLine(1);
        $this->info('Publish public');
        $this->call('vendor:publish', [
            '--provider' => 'Backpack\CRUD\BackpackServiceProvider',
            '--tag' => 'public',
        ]);
        $this->progressBar->advance();

        $this->newLine(1);
        $this->info('Publish Gravatar');
        $this->call('vendor:publish', [
            '--provider' => 'Backpack\CRUD\BackpackServiceProvider',
            '--tag' => 'gravatar',
        ]);
        $this->progressBar->advance();

        $this->newLine(1);
        $this->info('Backpack:publish-middleware');
        $this->call('backpack:publish-middleware');
        $this->progressBar->advance();

     

        $this->newLine(1);
        $this->info('vendor:publish cms_menu_content');
        $this->call('vendor:publish', [
            '--tag' => 'cms_menu_content',
            '--force' => true
        ]);
        $this->progressBar->advance();

        $this->newLine(1);
        $this->info('vendor:publish players');
        $this->call('vendor:publish', [
            '--tag' => 'players',
        ]);
        $this->progressBar->advance();
        $this->progressBar->finish();
        $this->info('Ophim installation finished.');
        return 0;
    }
}
