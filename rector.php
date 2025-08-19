<?php

declare(strict_types=1);

use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
        naming: true,
    )
    ->withPhpSets(php82: true)
    ->withConfiguredRule(AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector::class, [
        new AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration('SomeClass', 'process', 0, 0, new StringType),
    ]);
