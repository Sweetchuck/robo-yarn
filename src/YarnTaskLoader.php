<?php

namespace Cheppers\Robo\Yarn;

use Robo\Collection\CollectionBuilder;

trait YarnTaskLoader
{
    /**
     * @return \Cheppers\Robo\Yarn\Task\YarnInstallTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskYarnInstall(array $options = []): CollectionBuilder
    {
        return $this->task(Task\YarnInstallTask::class, $options);
    }

    /**
     * @return \Cheppers\Robo\Yarn\Task\YarnVersionTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskYarnVersion(array $options = []): CollectionBuilder
    {
        return $this->task(Task\YarnVersionTask::class, $options);
    }
}
