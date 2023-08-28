<?php

namespace Wepa\Procedures\Commands;

use Illuminate\Console\Command;

class ProceduresDemoCommand extends Command
{
    public $description = 'Demo procedures module';

    public string $package = 'procedures';

    public $signature = 'procedures:demo';

    protected array $vendor = [];

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
