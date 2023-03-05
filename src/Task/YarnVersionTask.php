<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

class YarnVersionTask extends BaseCliTask
{
    protected string $taskName = 'Yarn - Version';

    protected string $action = '';

    protected array $assets = [
        'version' => null,
    ];

    protected function getOptions(): array
    {
        return [
            'version' => [
                'type' => 'flag',
                'value' => true,
            ],
        ] + parent::getOptions();
    }

    protected function runProcessOutputs(): static
    {
        if ($this->actionExitCode === 0) {
            $this->assets['version'] = trim($this->actionStdOutput);
        }

        return $this;
    }
}
