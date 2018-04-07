<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Task;

use Mindscreen\YarnLock\YarnLock;

class NodeVersionTask extends BaseTask
{
    /**
     * {@inheritdoc}
     */
    protected function runAction()
    {
        $options = $this->getOptions();
        $wd = $options['workingDirectory']['value'] ?? '.';

        $yarnLockFilePath = "$wd/yarn.lock";
        if (file_exists($yarnLockFilePath)) {
            $this->runActionYarnLock($yarnLockFilePath);
        }

        $packageLockFilePath = "$wd/package-lock.json";
        if (!isset($this->assets['node.version']) && file_exists($packageLockFilePath)) {
            $this->runActionPackageLock($packageLockFilePath);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function runActionYarnLock(string $filePath)
    {
        $this->assets['node.version'] = null;

        try {
            $yarnLock = YarnLock::fromString(file_get_contents($filePath));
        } catch (\Exception $e) {
            return $this;
        }

        if ($yarnLock->hasPackage('node')) {
            $this->assets['node.version'] = $yarnLock->getPackage('node')->getVersion();
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function runActionPackageLock(string $filePath)
    {
        $lockContent = file_get_contents($filePath);
        if (!is_string($lockContent)) {
            throw new \Exception('@todo', 1);
        }

        $lock = json_decode($lockContent, true);
        if ($lock === null) {
            throw new \Exception('@todo', 2);
        }

        $this->assets['node.version'] = $lock['dependencies']['node']['version'] ?? null;

        return $this;
    }
}
