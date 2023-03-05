<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

class YarnInstallTask extends CommonCliTask
{
    protected string $taskName = 'Yarn - Install';

    protected string $action = 'install';

    // region Option - skipIfPackageJsonNotExists.
    protected bool $skipIfPackageJsonNotExists = false;

    public function getSkipIfPackageJsonNotExists(): bool
    {
        return $this->skipIfPackageJsonNotExists;
    }

    public function setSkipIfPackageJsonNotExists(bool $value): static
    {
        $this->skipIfPackageJsonNotExists = $value;

        return $this;
    }
    // endregion

    public function setOptions(array $options): static
    {
        parent::setOptions($options);
        if (array_key_exists('skipIfPackageJsonNotExists', $options)) {
            $this->setSkipIfPackageJsonNotExists($options['skipIfPackageJsonNotExists']);
        }

        return $this;
    }

    protected function runHeader(): static
    {
        if (!$this->isPackageJsonExists() && $this->getSkipIfPackageJsonNotExists()) {
            $this->printTaskInfo(
                'Skip "yarn install" in "<info>{workingDirectory}</info>"',
                [
                    'workingDirectory' => $this->getWorkingDirectory() ?: '.',
                ]
            );

            return $this;
        }

        return parent::runHeader();
    }

    protected function runAction(): static
    {
        return (!$this->isPackageJsonExists() && $this->getSkipIfPackageJsonNotExists()) ?
            $this
            : parent::runAction();
    }

    protected function isPackageJsonExists(): bool
    {
        $wd = $this->getWorkingDirectory() ?: '.';

        return file_exists("$wd/package.json");
    }
}
