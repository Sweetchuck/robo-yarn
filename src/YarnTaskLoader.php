<?php

namespace Sweetchuck\Robo\Yarn;

use League\Container\ContainerAwareInterface;
use Robo\Collection\CollectionBuilder;

trait YarnTaskLoader
{
    /**
     * @return \Sweetchuck\Robo\Yarn\Task\NodeVersionTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskYarnNodeVersion(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Yarn\Task\NodeVersionTask $task */
        $task = $this->task(Task\NodeVersionTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $container = $this->getContainer();
            if ($container) {
                $task->setContainer($this->getContainer());
            }
        }

        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\Yarn\Task\YarnInstallTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskYarnInstall(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Yarn\Task\YarnInstallTask $task */
        $task = $this->task(Task\YarnInstallTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $container = $this->getContainer();
            if ($container) {
                $task->setContainer($this->getContainer());
            }
        }

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
        if ($this instanceof ContainerAwareInterface) {
            $container = $this->getContainer();
            if ($container) {
                $task->setContainer($this->getContainer());
            }
        }

        $task->setOptions($options);

        return $task;
    }
}
