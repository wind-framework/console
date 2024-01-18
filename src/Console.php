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

        $app = WindApplication::getInstance();
        $app->startComponents();

        $console = new Application('Wind Framework Console');

        $app->container->set(Application::class, $console);
        $app->container->call(static::installCommands(...));

        $console->run();
    }

    protected static function installCommands(Application $application, Config $config)
    {
        $scanner = new ClassScanner(ClassScanner::TYPE_CLASS);
        $scanner->addNamespace('\\Wind\Console\\Command', __DIR__.'/Command');

        $scanMap = $config->get('console.command.scan_ns_paths');
        $scanMap && $scanner->addMap($scanMap);

        foreach ($scanner->scan() as $ref) {
            if ($ref->isSubclassOf(Command::class)) {
                $application->add($ref->newInstance());
            }
        }
    }

}
