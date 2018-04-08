<?php

namespace Sweetchuck\Robo\Yarn\Task;

class YarnInstallTask extends CommonCliTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Yarn - Install';

    /**
     * {@inheritdoc}
     */
    protected $action = 'install';

    // region Option - skipIfPackageJsonNotExists.
    /**
     * @var bool
     */
    protected $skipIfPackageJsonNotExists = false;

    public function getSkipIfPackageJsonNotExists(): bool
    {
        return $this->skipIfPackageJsonNotExists;
    }

    /**
     * @return $this
     */
    public function setSkipIfPackageJsonNotExists(bool $value)
    {
        $this->skipIfPackageJsonNotExists = $value;

        return $this;
    }
    // endregion

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
        if (array_key_exists('skipIfPackageJsonNotExists', $options)) {
            $this->setSkipIfPackageJsonNotExists($options['skipIfPackageJsonNotExists']);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runHeader()
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

    /**
     * {@inheritdoc}
     */
    protected function runAction()
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
