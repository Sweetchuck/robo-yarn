<?php

namespace Cheppers\Robo\Yarn\Option;

trait BaseOptions
{
    // region Option - workingDirectory.
    /**
     * @var string
     */
    protected $workingDirectory = '';

    public function getWorkingDirectory(): string
    {
        return $this->workingDirectory;
    }

    /**
     * @return $this
     */
    public function setWorkingDirectory(string $value)
    {
        $this->workingDirectory = $value;

        return $this;
    }
    // endregion

    // region Option - yarnExecutable.
    /**
     * @var string
     */
    protected $yarnExecutable = 'yarn';

    public function getYarnExecutable(): string
    {
        return $this->yarnExecutable;
    }

    /**
     * @return $this
     */
    public function setYarnExecutable(string $value)
    {
        $this->yarnExecutable = $value;

        return $this;
    }
    // endregion

    protected function getOptionsBase(): array
    {
        return [
            'workingDirectory' => [
                'type' => 'other',
                'value' => $this->getWorkingDirectory(),
            ],
            'yarnExecutable' => [
                'type' => 'other',
                'value' => $this->getYarnExecutable(),
            ],
        ];
    }

    /**
     * @return $this
     */
    protected function setOptionsBase(array $options)
    {
        foreach ($options as $name => $value) {
            switch ($name) {
                case 'assetJar':
                    $this->setAssetJar($value);
                    break;

                case 'assetJarMapping':
                    $this->setAssetJarMapping($value);
                    break;

                case 'workingDirectory':
                    $this->setWorkingDirectory($value);
                    break;

                case 'yarnExecutable':
                    $this->setYarnExecutable($value);
                    break;
            }
        }

        return $this;
    }
}
