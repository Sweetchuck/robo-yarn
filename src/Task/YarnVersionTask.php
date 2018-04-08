<?php

namespace Sweetchuck\Robo\Yarn\Task;

class YarnVersionTask extends BaseCliTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Yarn - Version';

    /**
     * {@inheritdoc}
     */
    protected $action = '';

    /**
     * {@inheritdoc}
     */
    protected $assets = [
        'version' => null,
    ];

    /**
     * {@inheritdoc}
     */
    protected function getOptions(): array
    {
        return [
            'version' => [
                'type' => 'flag',
                'value' => true,
            ],
        ] + parent::getOptions();
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        if ($this->actionExitCode === 0) {
            $this->assets['version'] = trim($this->actionStdOutput);
        }

        return $this;
    }
}
