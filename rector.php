<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $services = $containerConfigurator->services();

    # Options
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    # PHP Rules
    $containerConfigurator->import(LevelSetList::UP_TO_PHP_74);

    $services->remove(AddLiteralSeparatorToNumberRector::class);
    $services->remove(ThisCallOnStaticMethodToStaticCallRector::class);

    $services->set(TypedPropertyRector::class)->configure([
        TypedPropertyRector::INLINE_PUBLIC => true
    ]);
};
