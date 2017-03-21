<?php

namespace EMedia\Helpers\Console\Commands\Database;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshDatabaseCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:refresh {--nomigrate} {--noseed} {--modules}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove all tables, migrate and seed all data';

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
		$this->cleanUpDatabase();

		if ($this->option('nomigrate')) return;

		$this->call('migrate');
		if ($this->option('modules')) $this->call('module:migrate');

		if (!$this->option('noseed')) {
			$this->call('db:seed');
			if ($this->option('modules')) $this->call('module:seed');
		}
	}

	private function cleanUpDatabase()
	{
		$driver = DB::connection()->getConfig('driver');
		if ($driver === 'pgsql') {
			$this->cleanUpDatabasePostgres();
		} elseif ($driver === 'mysql') {
			$this->cleanUpDatabaseMySQL();
		} else {
			$this->error("Unable to load logic for the driver `$driver`");
		}
	}

	/**
	 *
	 * Clean up the database
	 * https://laracasts.com/discuss/channels/laravel/drop-all-tables-instead-of-migration-rollback
	 *
	 */
	private function cleanUpDatabaseMySQL()
	{
		$db = DB::connection()->getDatabaseName();
		$colname = 'Tables_in_' . $db;

		$tables = DB::select('SHOW TABLES');

		if (!count($tables)) {
			$this->info("Database `$db` is already clear.");
			return;
		}

		$droplist = [];
		foreach($tables as $table) {
			$droplist[] = $table->$colname;

		}
		$droplist = implode(',', $droplist);

		DB::beginTransaction();
		//turn off referential integrity
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		DB::statement("DROP TABLE $droplist");
		//turn referential integrity back on
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
		DB::commit();

		$this->comment(PHP_EOL."If no errors showed up, all tables were dropped".PHP_EOL);

	}

	/**
	 *	https://laracasts.com/discuss/channels/laravel/drop-all-tables-instead-of-migration-rollback?page=2
	 *	This code is only for reference, not tested
	 */
	private function cleanUpDatabasePostgres()
	{
		$query = 'SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname=\'public\'';
		$tables = array_column(DB::select($query), 'tablename');

		foreach ($tables as $table) {
			DB::statement('drop table '.$table.' cascade');
		}
	}
}
