<?php

namespace Sweetchuck\Robo\Yarn\Task;

use Sweetchuck\Robo\Yarn\Option\BaseOptions;
use Sweetchuck\Robo\Yarn\Utils;
use Robo\Common\OutputAwareTrait;
use Robo\Contract\CommandInterface;
use Symfony\Component\Process\Process;

abstract class BaseCliTask extends BaseTask implements CommandInterface
{
    use OutputAwareTrait;
    use BaseOptions;

    /**
     * @var string
     */
    protected $action = '';

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string
     */
    protected $processClass = Process::class;

    // region Option - processTimeout
    /**
     * @var null|float
     */
    protected $processTimeout = null;

    public function getProcessTimeout(): ?float
    {
        return $this->processTimeout;
    }

    /**
     * @return $this
     */
    public function setProcessTimeout(?float $processTimeout)
    {
        $this->processTimeout = $processTimeout;

        return $this;
    }
    // endregion

    protected $envVars = [];

    public function getEnvVars(): array
    {
        return $this->envVars;
    }

    public function getEnvVar(string $name): ?string
    {
        return $this->envVars[$name] ?? null;
    }

    /**
     * @return $this
     */
    public function setEnvVars(array $envVars)
    {
        $this->envVars = $envVars;

        return $this;
    }

    /**
     * @return $this
     */
    public function setEnvVar(string $name, $value)
    {
        $this->envVars[$name] = (string) $value;

        return $this;
    }

    public function setOptions(array $options)
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
                        $envPattern[] = "{$optionName}=%s";
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
                    $items = Utils::filterEnabled($option['value']);
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

    protected function runPrepare()
    {
        parent::runPrepare();
        $this->command = $this->getCommand();

        return $this;
    }

    /**
     * @return $this
     */
    protected function runHeader()
    {
        $this->printTaskInfo($this->command);

        return $this;
    }

    /**
     * @return $this
     */
    protected function runAction()
    {
        $x = 0;
        /** @var \Symfony\Component\Process\Process $process */
        $process = new $this->processClass(
            $this->command,
            null,
            $this->getEnvVars() ?: null,
            null,
            $this->getProcessTimeout()
        );

        $this->actionExitCode = $process->run(function ($type, $data) {
            $this->runCallback($type, $data);
        });
        $this->actionStdOutput = $process->getOutput();
        $this->actionStdError = $process->getErrorOutput();

        return $this;
    }
}
