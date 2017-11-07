<?php

namespace SilverStripe\ApiDocs\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
{
    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Running saml command...");

        set_time_limit(0);

        /** @var ProcessHelper $runner */
        $runner = $this->getHelper('process');
        $result = $runner->run($output, [
            'bin/sami',
            'update',
            'conf/sami.php',
            '--force'
        ]);
        if (!$result->isSuccessful()) {
            $error = $result->getErrorOutput();
            throw new Exception($error);
        }

        $output->writeln("Saml generated!");
    }
}
