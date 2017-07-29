<?php

namespace Sweetchuck\Robo\Yarn\Tests\Task;

use Sweetchuck\Robo\Yarn\Test\AcceptanceTester;
use Sweetchuck\Robo\Yarn\Test\Helper\RoboFiles\YarnRoboFile;
use Symfony\Component\Filesystem\Filesystem;

class YarnTaskCest
{
    /**
     * @var string[]
     */
    protected $tmpDirs = [];

    /**
     * @var null|\Symfony\Component\Filesystem\Filesystem
     */
    protected $fs = null;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    public function __destruct()
    {
        $this->fs->remove($this->tmpDirs);
    }

    public function runInstallSuccess(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('01');

        $id = 'install';
        $I->runRoboTask($id, YarnRoboFile::class, 'install:success', $tmpDir);

        $expectedStdOutput = '';
        $expectedStdError = sprintf(
            " [YarnInstall] cd %s && yarn install\n",
            escapeshellarg($tmpDir)
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
    }

    public function runVersionSuccess(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('01');

        $id = 'version';
        $I->runRoboTask($id, YarnRoboFile::class, 'version:success', $tmpDir);

        $expectedStdError = sprintf(
            " [YarnVersion] cd %s && yarn --version\n",
            escapeshellarg($tmpDir)
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
    }

    protected function createTmpDir(string $fixture): string
    {
        $dirName = tempnam(sys_get_temp_dir(), 'robo-yarn.test.');
        if (unlink($dirName)) {
            mkdir($dirName, 0777 - umask(), true);
            $this->fs->mirror(
                codecept_data_dir("fixtures/$fixture"),
                $dirName
            );
        }

        $this->tmpDirs[] = $dirName;

        return $dirName;
    }
}
