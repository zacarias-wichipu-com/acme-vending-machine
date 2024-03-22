<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/apps')
    ->in(__DIR__ . '/bin')
    ->in(__DIR__ . '/features')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRules([
//        '@PHP83Migration' => true,
//        '@PSR12' => true,
//        '@Symfony' => true,
//        'global_namespace_import' => [
//            'import_classes' => false,
//            'import_constants' => false,
//            'import_functions' => false,
//        ],
//        'no_unused_imports' => true,
//        'single_line_after_imports' => true,
//        'blank_line_between_import_groups' => true,
//        'single_quote' => true,
//        'yoda_style' => true,
    ])
    ->setFinder($finder);
