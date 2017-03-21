<?php


namespace EMedia\Helpers\Console\Commands;


use EMedia\Helpers\Console\Commands\Traits\CopiesStubFiles;
use Illuminate\Console\Command;

abstract class BasePackageSetupCommand extends Command
{

	use CopiesStubFiles;

	public function handle()
	{
		$this->generateMigrations();
		$this->generateSeeds();
		$this->dumpAutoload();
	}

	abstract protected function generateMigrations();

	abstract protected function generateSeeds();

}