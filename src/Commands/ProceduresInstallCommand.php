<?php

namespace Wepa\Procedures\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Wepa\Core\Models\Menu;

class ProceduresInstallCommand extends Command
{
    public $description = 'Install procedures module';

    public string $package = 'procedures';

    public $signature = 'procedures:install';

    protected array $vendor = [];

    public function handle(): int
    {
        $this->call('migrate');
        $this->call('vendor:publish', ['--tag' => 'procedures', '--force' => true]);

        Menu::loadPackageItems($this->package);

        $this->call('db:seed', ['class' => 'Wepa\Faq\Database\Seeders\DefaultSeeder']);

        $process = Process::fromShellCommandline('npm i -D @ckeditor/ckeditor5-vue wepa-ckeditor5-filemanager');
        $process->run();
        $this->info($process->getOutput());

        return self::SUCCESS;
    }
}
