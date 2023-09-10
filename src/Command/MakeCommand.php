<?php

namespace Wind\Console\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Make a command command
 */
class MakeCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('make:command')
            ->setDescription('Create a console command.')
            ->addArgument('name', InputArgument::REQUIRED, 'Command name like \'user:info\'')
            ->addArgument('className', InputArgument::REQUIRED, 'Command class name like \'UserInfoCommand\'?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $className = $input->getArgument('className');

        $filepath = BASE_DIR.'/app/Command/'.$className.'.php';

        if (file_exists($filepath)) {
            $output->writeln("<error>Command file '.$filepath.' is already exists!</>");
            return self::INVALID;
        }

        if (di()->get(Application::class)->has($name)) {
            $output->writeln("<error>Command name '.$name' is already exists!</>");
            return self::INVALID;
        }

        $code = <<<COMMAND
<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class $className extends Command
{

    protected function configure()
    {
        \$this->setName('$name')
            ->setDescription('Command description.');
    }

    protected function execute(InputInterface \$input, OutputInterface \$output)
    {
        \$output->writeln('Hello World');
        return self::SUCCESS;
    }

}

COMMAND;

        if (file_put_contents($filepath, $code) === false) {
            $output->writeln("<error>Write command file '.$filepath' failed.</error>");
            return self::FAILURE;
        }

        $output->writeln("<info>Create command $name successfully at $filepath.</info>");

        return self::SUCCESS;
    }

}
