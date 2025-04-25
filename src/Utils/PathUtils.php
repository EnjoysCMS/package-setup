<?php

declare(strict_types=1);


namespace Enjoyscms\PackageSetup\Utils;


final class PathUtils
{

    public static function normalizePath(string $path, string $rootPath, string $cwd): string
    {
        return match (true) {
            \str_starts_with($path, './') => $cwd . ltrim($path, '.'),
            \str_starts_with($path, '~/') => $cwd . '/' . ltrim($path, '~/'),
            \str_starts_with($path, '/') => $rootPath . '/' . ltrim($path, '/'),
            default => $cwd . '/' . trim($path),
        };
    }
}