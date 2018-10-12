<?php


namespace EMedia\Helpers\Console\Commands\Production;


use Illuminate\Console\Command;

class ConnectDeployKeysCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'setup:production:connect-deploy-keys 
								{--public-key-path : Your public key to be used}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add SSH public key to Bitbucket private repositories.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed|void
	 */
	public function handle()
	{
		$schemaPath = base_path('composer.json');
		$publicKeyPath = $this->option('public-key-path');

		if (file_exists($publicKeyPath)) {
			$publicKey = file_get_contents($publicKeyPath);
		} else {
			$publicKey = $this->ask('What is the PUBLIC KEY to be used?');
		}

		if (!file_exists($schemaPath)) {
			$this->error("Cannot read file `$schemaPath`. Aborting...");
			return;
		}

		$json = json_decode(file_get_contents($schemaPath));
		if (empty($json->repositories)) {
			$this->info('No private repositories found. Aborting...');
			return;
		}

		$appName = config('app.name');
		if ($appName === 'Laravel' || empty($appName)) {
			$this->error('Set the app name in your .env file first. Aborting...');
			return;
		}

		if (strpos($publicKey, 'PRIVATE') !== false) {
			$this->error('Seems like you have entered a PRIVATE key. Do not distribute PRIVATE KEYS!!!. Aborting...');
			return;
		}

		$label = snake_case($appName) . '_' . php_uname("n");

		$label = $this->ask('Enter a label for the key', $label);
		if (!$this->confirm("Add all keys with a label `{$label}`?", false)) {
			$this->error('Aborting...');
			return;
		}

		$this->info('Adding keys requires a BitBucket account with admin access to repositories.');
		$username = $this->ask('Your Bitbucket Username');
		$password = $this->secret('Your Bitbucket Password');


		check_all_present($username, $password, $label, $publicKey);

		foreach ($json->repositories as $repository) {
			if (strtolower($repository->type) !== 'vcs') {
				$this->info("Skipping non VCS URL `{$repository->url}`");
				continue;
			}

			$sections = explode(':', $repository->url);
			if (count($sections) === 2) {
				$slug = substr($sections[1], 0, strrpos($sections[1], '.'));
				$this->info('Adding key to: ' . $slug);
				$ch = curl_init('https://api.bitbucket.org/1.0/repositories/' . $slug . '/deploy-keys');
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
				curl_setopt($ch, CURLOPT_POSTFIELDS, [
					'label' => $label,
					'key' => $publicKey,
				]);

				$response = json_decode(curl_exec($ch));
				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				if ($code < 200 || $code >= 300) {
					if (isset($response->key) && count($response->key)) {
						foreach ($response->key as $info) {
							if(isset($info->message)) {
								$this->info($info->message);
							}
						}
					} else {
						print_r($response);
					}
				} else {
					$this->info('Key successfully added to: ' . $slug);
				}

				curl_close($ch);
			}
		}
	}

}