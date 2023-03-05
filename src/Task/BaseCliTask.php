<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

use Sweetchuck\Robo\Yarn\Option\BaseOptions;
use Robo\Common\OutputAwareTrait;
use Robo\Contract\CommandInterface;
use Sweetchuck\Utils\Filter\ArrayFilterEnabled;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Process\Process;

abstract class BaseCliTask extends BaseTask implements CommandInterface
{
    use OutputAwareTrait;
    use BaseOptions;

    protected string $action = '';

    protected string $command = '';

    /**
     * @var null|callable
     */
    protected $processRunCallbackWrapper = null;

    // region Option - processTimeout
    protected null|int|float $processTimeout = null;

    public function getProcessTimeout(): null|int|float
    {
        return $this->processTimeout;
    }

    public function setProcessTimeout(null|int|float $processTimeout): static
    {
        $this->processTimeout = $processTimeout;

        return $this;
    }
    // endregion

    // region Option - envVars
    protected array $envVars = [];

    public function getEnvVars(): array
    {
        return $this->envVars;
    }

    public function getEnvVar(string $name): ?string
    {
        return $this->envVars[$name] ?? null;
    }

    public function setEnvVars(array $envVars): static
    {
        $this->envVars = $envVars;

        return $this;
    }

    public function setEnvVar(string $name, $value): static
    {
        $this->envVars[$name] = (string) $value;

        return $this;
    }

    // endregion

    public function setOptions(array $options): static
    {
        parent::setOptions($options);

        if (array_key_exists('processTimeout', $options)) {
            $this->setProcessTimeout($options['processTimeout']);
        }

        if (array_key_exists('envVars', $options)) {
            $this->setEnvVars($options['envVars']);
        }

        return $this;
    }

    protected function getOptions(): array
    {
        $options = parent::getOptions();
        $options['processTimeout'] = [
            'type' => 'other',
            'value' => $this->getProcessTimeout(),
        ];

        foreach ($this->getEnvVars() as $name => $value) {
            $options["envVar:$name"] = [
                'type' => 'environment',
                'name' => $name,
                'value' => $value,
            ];
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        $options = $this->getOptions();

        $envPattern = [];
        $envArgs = [];

        $cmdPattern = [];
        $cmdArgs = [];

        $cmdAsIs = [];

        if ($options['nodeExecutable']['value']) {
            $cmdPattern[] = '%s';
            $cmdArgs[] = escapeshellcmd($options['nodeExecutable']['value']);
        }

        $cmdPattern[] = '%s';
        $cmdArgs[] = escapeshellcmd($options['yarnExecutable']['value']);

        if ($this->action) {
            $cmdPattern[] = $this->action;
        }

        foreach ($options as $optionName => $option) {
            switch ($option['type']) {
                case 'environment':
                    if ($option['value'] !== null) {
                        $envVarName = $option['name'] ?? $optionName;
                        $envPattern[] = "{$envVarName}=%s";
                        $envArgs[] = escapeshellarg($option['value']);
                    }
                    break;

                case 'int':
                    if ($option['value']) {
                        $cmdPattern[] = "--$optionName %d";
                        $cmdArgs[] = $option['value'];
                    }
                    break;

                case 'value':
                    if ($option['value']) {
                        $cmdPattern[] = "--$optionName %s";
                        $cmdArgs[] = escapeshellarg($option['value']);
                    }
                    break;

                case 'value-optional':
                    if ($option['value'] !== null) {
                        $value = (string) $option['value'];
                        if ($value === '') {
                            $cmdPattern[] = "--{$optionName}";
                        } else {
                            $cmdPattern[] = "--{$optionName} %s";
                            $cmdArgs[] = escapeshellarg($value);
                        }
                    }
                    break;

                case 'flag':
                    if ($option['value']) {
                        $cmdPattern[] = "--$optionName";
                    }
                    break;

                case 'tri-state':
                    if ($option['value'] !== null) {
                        $cmdPattern[] = $option['value'] ? "--$optionName" : "--no-$optionName";
                    }
                    break;

                case 'true|false':
                    $nameFilter = array_combine(
                        explode('|', $optionName),
                        [true, false]
                    );

                    foreach ($nameFilter as $name => $filter) {
                        $items = array_keys($option['value'], $filter, true);
                        if ($items) {
                            $cmdPattern[] = "--$name=%s";
                            $cmdArgs[] = escapeshellarg(implode(' ', $items));
                        }
                    }
                    break;

                case 'space-separated':
                    $items = array_filter($options['value'], new ArrayFilterEnabled());
                    if ($items) {
                        $cmdPattern[] = "--$optionName %s";
                        $cmdArgs[] = escapeshellarg(implode(' ', $items));
                    }
                    break;

                case 'as-is':
                    if ($option['value'] instanceof CommandInterface) {
                        $cmd = $option['value']->getCommand();
                    } else {
                        $cmd = (string) $option['value'];
                    }

                    if ($cmd) {
                        $cmdAsIs[] = $cmd;
                    }
                    break;
            }
        }

        $wd = $this->getWorkingDirectory();

        $chDir = $wd ? sprintf('cd %s &&', escapeshellarg($wd)) : '';
        $env = vsprintf(implode(' ', $envPattern), $envArgs);
        $cmd = vsprintf(implode(' ', $cmdPattern), $cmdArgs);
        $asIs = implode(' ', $cmdAsIs);

        return implode(' ', array_filter([$chDir, $env, $cmd, $asIs]));
    }

    protected function runPrepare(): static
    {
        parent::runPrepare();
        $this->command = $this->getCommand();
        $this->processRunCallbackWrapper = function (string $type, string $data): void {
            $this->processRunCallback($type, $data);
        };

        return $this;
    }

    protected function runHeader(): static
    {
        $this->printTaskInfo($this->command);

        return $this;
    }

    protected function runAction(): static
    {
        // @todo Remove this once drupal/core uses symfony/process:^4.
        $processInner =  is_callable([Process::class, 'fromShellCommandline']) ?
            Process::fromShellCommandline($this->command)
            : new Process($this->command);

        $processInner->setTimeout($this->options['processTimeout']['value']);

        $process = $this
            ->getProcessHelper()
            ->run($this->output(), $processInner, null, $this->processRunCallbackWrapper);

        $this->actionExitCode = $process->getExitCode();
        $this->actionStdOutput = $process->getOutput();
        $this->actionStdError = $process->getErrorOutput();

        return $this;
    }

    protected function processRunCallback(string $type, string $data): void
    {
        switch ($type) {
            case Process::OUT:
                $this->output()->write($data);
                break;

            case Process::ERR:
                $this->printTaskError($data);
                break;
        }
    }

    protected function getProcessHelper(): ProcessHelper
    {
        return $this
            ->getContainer()
            ->get('application')
            ->getHelperSet()
            ->get('process');
    }
}
