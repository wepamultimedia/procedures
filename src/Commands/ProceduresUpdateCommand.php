<?php

namespace Wepa\Procedures\Commands;

use Illuminate\Console\Command;

class ProceduresUpdateCommand extends Command
{
    public $description = 'Update procedures module';

    public string $package = 'procedures';

    public $signature = 'procedures:update';

    protected array $vendor = [];

    public function handle(): int
    {
        $this->call('migrate');
        $this->call('vendor:publish', ['--tag' => 'procedures', '--force' => true]);

        return self::SUCCESS;
    }
}
