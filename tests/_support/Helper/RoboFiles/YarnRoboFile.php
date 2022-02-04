<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Tests\Helper\RoboFiles;

use Sweetchuck\Robo\Yarn\YarnTaskLoader;
use Robo\Contract\TaskInterface;
use Robo\Tasks;

class YarnRoboFile extends Tasks
{
    use YarnTaskLoader;

    public function install(
        string $dir,
        array $options = [
            'skipIfPackageJsonNotExists' => false,
        ]
    ): TaskInterface {
        return $this
            ->taskYarnInstall()
            ->setWorkingDirectory($dir)
            ->setSkipIfPackageJsonNotExists($options['skipIfPackageJsonNotExists']);
    }

    public function versionSuccess(string $dir): TaskInterface
    {
        return $this
            ->taskYarnVersion()
            ->setWorkingDirectory($dir);
    }
}
