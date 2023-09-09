<?php

namespace Wind\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Wind\Base\Application as WindApplication;
use Wind\Base\ClassScanner;
use Wind\Base\Config;

/**
 * Wind Framework Console
 */
class Console
{

    public static function run()
    {
        WindApplication::start('console');

        $container = WindApplication::getInstance()->container;

        $application = new Application('Wind Framework Console');

        $container->set(Application::class, $application);

        $container->call(static::installCommands(...));

        $application->run();
    }

    protected static function installCommands(Application $application, Config $config)
    {
        $scanner = new ClassScanner(ClassScanner::TYPE_CLASS);
        $scanner->addNamespace('\\Wind\Console\\Command', __DIR__.'/Command');

        $scanMap = $config->get('commands.scan');
        $scanMap && $scanner->addMap($scanMap);

        foreach ($scanner->scan() as $ref) {
            if ($ref->isSubclassOf(Command::class)) {
                $application->add($ref->newInstance());
            }
        }
    }

}
