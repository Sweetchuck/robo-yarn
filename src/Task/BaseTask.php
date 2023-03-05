<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

use Consolidation\AnnotatedCommand\Output\OutputAwareInterface;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Sweetchuck\Robo\Yarn\Option\BaseOptions;
use Robo\Common\OutputAwareTrait;
use Robo\Result;
use Robo\Task\BaseTask as RoboBaseTask;
use Robo\TaskInfo;

abstract class BaseTask extends RoboBaseTask implements ContainerAwareInterface, OutputAwareInterface
{
    use ContainerAwareTrait;
    use OutputAwareTrait;
    use BaseOptions;

    protected string $taskName = '';

    protected array $options = [];

    protected int $actionExitCode = 0;

    protected string $actionStdOutput = '';

    protected string $actionStdError = '';

    protected array $assets = [];

    protected function getOptions(): array
    {
        return $this->getOptionsBase();
    }

    public function setOptions(array $options): static
    {
        return $this->setOptionsBase($options);
    }

    public function getTaskName(): string
    {
        return $this->taskName ?: TaskInfo::formatTaskName($this);
    }

    public function run()
    {
        return $this
            ->runPrepare()
            ->runHeader()
            ->runAction()
            ->runProcessOutputs()
            ->runReturn();
    }

    protected function runPrepare()
    {
        $this->options = $this->getOptions();

        return $this;
    }

    protected function runHeader(): static
    {
        return $this;
    }

    abstract protected function runAction(): static;

    protected function runProcessOutputs(): static
    {
        return $this;
    }

    protected function runReturn(): Result
    {
        return new Result(
            $this,
            $this->getTaskResultCode(),
            $this->getTaskResultMessage(),
            $this->getAssetsWithPrefixedNames()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTaskContext($context = null)
    {
        if (!$context) {
            $context = [];
        }

        if (empty($context['name'])) {
            $context['name'] = $this->getTaskName();
        }

        return parent::getTaskContext($context);
    }

    protected function getTaskResultCode(): int
    {
        return $this->actionExitCode;
    }

    protected function getTaskResultMessage(): string
    {
        return $this->actionStdError;
    }

    protected function getAssetsWithPrefixedNames(): array
    {
        $prefix = $this->getAssetNamePrefix();
        if ($prefix === '') {
            return $this->assets;
        }

        $assets = [];
        foreach ($this->assets as $key => $value) {
            $assets["{$prefix}{$key}"] = $value;
        }

        return $assets;
    }
}
