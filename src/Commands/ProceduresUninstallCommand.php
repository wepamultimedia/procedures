<?php

namespace Wepa\Procedures\Commands;

use Illuminate\Console\Command;
use Wepa\Core\Models\Menu;

class ProceduresUninstallCommand extends Command
{
    public $description = 'Uninstall procedures module';

    public string $package = 'procedures';

    public $signature = 'procedures:uninstall';

    protected array $vendor = [];

    public function handle(): int
    {
        Menu::removePackageItems($this->package);

        return self::SUCCESS;
    }
}
