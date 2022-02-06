<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

use Icecave\SemVer\Version as SemVerVersion;
use Exception;
use Mindscreen\YarnLock\YarnLock;
use Sweetchuck\Utils\Filesystem;
use Sweetchuck\Utils\VersionNumber;

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
     * @var ?string
     */
    protected $rootDirectory = null;

    public function getRootDirectory(): ?string
    {
        return $this->rootDirectory;
    }

    /**
     * @return $this
     */
    public function setRootDirectory(?string $value)
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

        $fileName = 'yarn.lock';
        $dir = Filesystem::findFileUpward($fileName, $wd, $rootDir);
        if ($dir !== null) {
            $this->runActionYarnLock("$dir/$fileName");
        }

        if (!isset($this->assets['full'])) {
            $fileName = 'package-lock.json';
            $dir = Filesystem::findFileUpward($fileName, $wd, $rootDir);
            if ($dir !== null) {
                $this->runActionPackageLock("$dir/$fileName");
            }
        }

        if (!isset($this->assets['full'])) {
            $fileName = '.nvmrc';
            $dir = Filesystem::findFileUpward($fileName, $wd, $rootDir);
            if ($dir !== null) {
                $this->runActionNvmRc("$dir/$fileName");
            }
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
            $yarnLock = YarnLock::fromString(Filesystem::fileGetContents($filePath));
        } catch (Exception $e) {
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
        $lock = json_decode(Filesystem::fileGetContents($filePath), true);
        if ($lock === null) {
            throw new Exception(json_last_error_msg(), json_last_error());
        }

        $this->assets['full'] = $lock['dependencies']['node']['version'] ?? null;

        return $this;
    }

    /**
     * @return $this
     */
    protected function runActionNvmRc(string $filePath)
    {
        $this->assets['full'] = trim(Filesystem::fileGetContents($filePath)) ?: null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        parent::runProcessOutputs();
        if (!empty($this->assets['full'])) {
            $version = VersionNumber::createFromString($this->assets['full']);
            $this->assets['versionNumber'] = $version;
            $this->assets['major'] = $version->major;
            $this->assets['minor'] = $version->minor;
            $this->assets['patch'] = $version->patch;
            $this->assets['preRelease'] = $version->preRelease;
            $this->assets['metadata'] = $version->metadata;
            $this->assets['base'] = $version->format($version::FORMAT_MA0DMI0DP0);
        }

        return $this;
    }
}
