<?php


namespace EMedia\Helpers\Console\Commands\Packages;


use EMedia\Helpers\Console\Commands\Traits\CopiesStubFiles;
use Illuminate\Console\Command;
use EMedia\PHPHelpers\Files\FileEditor;

abstract class BasePackageSetupCommand extends Command
{

	use CopiesStubFiles;

	// any name to display to the user
	protected $packageName;

	protected $updateRoutesFile = false;

	public function handle()
	{
		$this->checkSetupVariables();

		if (method_exists($this, 'beforeMigrations')) {
			$this->beforeMigrations();
		}

		$this->generateMigrations();

		if (method_exists($this, 'beforeSeeds')) {
			$this->beforeSeeds();
		}

		$this->generateSeeds();

		if (method_exists($this, 'publishPackageFiles')) {
			$this->publishPackageFiles();
		}

		if ($this->updateRoutesFile) $this->updateRouteFiles();

		$this->dumpAutoload();
	}

	protected function checkSetupVariables()
	{
		if (empty($this->packageName)) throw new \InvalidArgumentException("packageName variable must be set");
	}

	/**
	 *
	 * Any name to display to the user
	 *
	 * @return mixed
	 */
	// abstract protected function setPackageName();

	abstract protected function generateMigrations();

	abstract protected function generateSeeds();

	protected function getPackageBaseDir()
	{
		$class = new \ReflectionClass($this);
		$pathinfo = pathinfo($class->getFileName());

		return $pathinfo['dirname'] . '/../..';
	}

	protected function getRouteStubFilePaths()
	{
		$stubPath = $this->getPackageBaseDir() . '/Stubs/routes/web.php.stub';

		return [
			'routes' => base_path('routes/web.php'),
			'stub' => $stubPath,
		];
	}

	protected function updateRouteFiles()
	{
		$filePaths = $this->getRouteStubFilePaths();

		try {
			$bytes = FileEditor::appendStubIfSectionNotFound($filePaths['routes'], $filePaths['stub'], null, null, true);
		} catch (\EMedia\PHPHelpers\Exceptions\FileSystem\SectionAlreadyExistsException $ex) {
			if (!$this->confirm($this->packageName . " package routes are already in routes file. Add again?", false)) {
				return false;
			}

			$bytes = FileEditor::appendStub($filePaths['routes'], $filePaths['stub']);
		}
	}

}
