<?php

namespace Sweetchuck\Robo\Yarn;

use Robo\Collection\CollectionBuilder;

trait YarnTaskLoader
{
    /**
     * @return \Sweetchuck\Robo\Yarn\Task\YarnInstallTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskYarnInstall(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Yarn\Task\YarnInstallTask $task */
        $task = $this->task(Task\YarnInstallTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\Yarn\Task\YarnVersionTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskYarnVersion(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Yarn\Task\YarnVersionTask $task */
        $task = $this->task(Task\YarnVersionTask::class);
        $task->setOptions($options);

        return $task;
    }
}
