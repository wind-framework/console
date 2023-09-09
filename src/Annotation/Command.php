<?php

namespace Wind\Console\Annotation;

use Attribute;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Wind\Annotation\Collectable;

#[Attribute(Attribute::TARGET_CLASS)]
class Command implements Collectable
{

    public function collectClass(ReflectionClass $ref) {
        if ($ref->isSubclassOf(BaseCommand::class)) {
            di()->get(Application::class)->add($ref->newInstance());
        } else {
            throw new \RuntimeException("Command annotation error: class {$ref->getName()} is a valid command, not subclass of Symfony\\Component\\Console\\Command\\Command.");
        }
    }

    public function collectMethod(ReflectionMethod $ref) { }

}
