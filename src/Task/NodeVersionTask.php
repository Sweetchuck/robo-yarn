<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

use Icecave\SemVer\Version as SemVerVersion;
use Mindscreen\YarnLock\YarnLock;
use Sweetchuck\Robo\Yarn\Utils;

/**
 * This task detects that which NodeJS version should be used in a certain directory.
 */
class NodeVersionTask extends BaseTask
{

    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Yarn - Required NodeJS version';

    // region Options
    // region rootDirectory
    /**
     * @var string
     */
    protected $rootDirectory = '';

    public function getRootDirectory(): string
    {
        return $this->rootDirectory;
    }

    /**
     * @return $this
     */
    public function setRootDirectory(string $value)
    {
        $this->rootDirectory = $value;

        return $this;
    }
    // endregion
    // endregion

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        if (array_key_exists('rootDirectory', $options)) {
            $this->setRootDirectory($options['rootDirectory']);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptions(): array
    {
        return [
            'rootDirectory' => [
                'type' => 'other',
                'value' => $this->getRootDirectory(),
            ],
        ] + parent::getOptions();
    }

    /**
     * {@inheritdoc}
     */
    protected function runAction()
    {
        $options = $this->getOptions();
        $wd = $options['workingDirectory']['value'] ?: '.';
        $rootDir = $options['rootDirectory']['value'];

        $filePath = Utils::findFileUpward('yarn.lock', $wd, $rootDir);
        if ($filePath && file_exists($filePath)) {
            $this->runActionYarnLock($filePath);
        }

        $filePath = Utils::findFileUpward('package-lock.json', $wd, $rootDir);
        if (!isset($this->assets['full']) && $filePath && file_exists($filePath)) {
            $this->runActionPackageLock($filePath);
        }

        $filePath = Utils::findFileUpward('.nvmrc', $wd, $rootDir);
        if (!isset($this->assets['full']) && $filePath && is_readable($filePath)) {
            $this->runActionNvmRc($filePath);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function runActionYarnLock(string $filePath)
    {
        $this->assets['full'] = null;

        try {
            $yarnLock = YarnLock::fromString(file_get_contents($filePath));
        } catch (\Exception $e) {
            return $this;
        }

        if ($yarnLock->hasPackage('node')) {
            $this->assets['full'] = $yarnLock->getPackage('node')->getVersion();
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function runActionPackageLock(string $filePath)
    {
        $fileContent = file_get_contents($filePath);
        if (!is_string($fileContent)) {
            throw new \Exception('@todo', 1);
        }

        $lock = json_decode($fileContent, true);
        if ($lock === null) {
            throw new \Exception('@todo', 2);
        }

        $this->assets['full'] = $lock['dependencies']['node']['version'] ?? null;

        return $this;
    }

    /**
     * @return $this
     */
    protected function runActionNvmRc(string $filePath)
    {
        $fileContent = file_get_contents($filePath);
        if (!is_string($fileContent)) {
            throw new \Exception('@todo', 1);
        }

        $this->assets['full'] = trim($fileContent) ?: null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        parent::runProcessOutputs();
        if (!empty($this->assets['full'])) {
            $version = SemVerVersion::parse($this->assets['full']);
            $this->assets['semVerVersion'] = $version;
            $this->assets['major'] = $version->major();
            $this->assets['minor'] = $version->minor();
            $this->assets['patch'] = $version->patch();
            $this->assets['preReleaseVersion'] = $version->preReleaseVersion();
            $this->assets['buildMetaData'] = $version->buildMetaData();
            $this->assets['base'] = sprintf('%d.%d.%d', $version->major(), $version->minor(), $version->patch());
        }

        return $this;
    }
}
