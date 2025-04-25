<?php

declare(strict_types=1);

namespace Test\Enjoyscms\PackageSetup\Utils;

use Enjoyscms\PackageSetup\Utils\PathUtils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PathUtilsTest extends TestCase
{

    public static function dataForTestNormalizePath(): array
    {
        return [
            ['/var/www/test/test', 'test', '/var/www', '/var/www/test'],
            ['/var/www/test/test', './test', '/var/www', '/var/www/test'],
            ['/var/www/test', '/test', '/var/www', '/var/www/test'],
            ['/var/www/test/test', '~/test', '/var/www', '/var/www/test'],
        ];
    }

    #[DataProvider('dataForTestNormalizePath')]
    public function testNormalizePath($expect, $path, $rootPath, $cwd): void
    {
        $this->assertSame($expect, PathUtils::normalizePath($path, $rootPath, $cwd));
    }
}
