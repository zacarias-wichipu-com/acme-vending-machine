<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
                   ->withPaths([
                       __DIR__.'/apps',
                       __DIR__.'/bin',
                       __DIR__.'/src',
                       __DIR__.'/tests',
                   ])
                   ->withPhpSets()
                   ->withSets([
                       LevelSetList::UP_TO_PHP_83,
                       SymfonySetList::SYMFONY_64,
                       SymfonySetList::SYMFONY_CODE_QUALITY,
                       SetList::TYPE_DECLARATION,
                   ]);
