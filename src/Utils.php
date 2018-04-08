<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn;

use Webmozart\PathUtil\Path;

class Utils
{
    public static function filterEnabled(array $items): array
    {
        return (gettype(reset($items)) === 'boolean') ?
            array_keys($items, true, true)
            : $items;
    }

    public static function findFileUpward(string $fileName, string $currentDir, string $rootDir = ''): ?string
    {
        if ($rootDir && !static::isParentDirOrSame($rootDir, $currentDir)) {
            throw new \InvalidArgumentException("The '$rootDir' is not parent dir of '$currentDir'");
        }

        while ($currentDir && (!$rootDir || static::isParentDirOrSame($rootDir, $currentDir))) {
            if (file_exists("$currentDir/$fileName")) {
                return "$currentDir/$fileName";
            }

            $parentDir = Path::getDirectory($currentDir);
            if ($currentDir === $parentDir) {
                break;
            }

            $currentDir = $parentDir;
        }

        return null;
    }

    public static function isParentDirOrSame(string $parentDir, string $childDir): bool
    {
        $pattern = '@^' . preg_quote($parentDir, '@') . '(/|$)@';

        return (bool) preg_match($pattern, $childDir);
    }
}
