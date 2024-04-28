<?php

namespace App\Entrypoint\Command\Dashboard\Security;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'dashboard:jwt:generate-keypair')]
class GenerateKeyPair extends Command
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/config')]
        private string $configDir
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $privateKey = openssl_pkey_new();
        openssl_pkey_export($privateKey, $privateKeyPEM);

        $publicKey = openssl_pkey_get_details($privateKey)['key'];

        $privateKeyFilePath = $this->configDir.'/secret/jwt/private_key.pem';
        file_put_contents($privateKeyFilePath, $privateKeyPEM);

        $publicKeyFilePath = $this->configDir.'/secret/jwt/public_key.pem';
        file_put_contents($publicKeyFilePath, $publicKey);

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
