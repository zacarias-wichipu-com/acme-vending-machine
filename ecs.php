<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
                ->withPaths([
                    __DIR__.'/apps',
                    __DIR__.'/src',
                    __DIR__.'/tests',
                ])
                ->withPhpCsFixerSets(
                    perCS: true
                );
