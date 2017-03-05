<?php

namespace Cheppers\Robo\Yarn\Test\Helper\RoboFiles;

use Cheppers\Robo\Yarn\YarnTaskLoader;
use Robo\Contract\TaskInterface;
use Robo\Tasks;

class YarnRoboFile extends Tasks
{
    use YarnTaskLoader;

    public function installSuccess(string $dir): TaskInterface
    {
        return $this->taskYarnInstall([
            'workingDirectory' => $dir,
        ]);
    }

    public function versionSuccess(string $dir): TaskInterface
    {
        return $this->taskYarnVersion([
            'workingDirectory' => $dir,
        ]);
    }
}
