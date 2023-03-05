<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Tests\Acceptance\Task;

use Sweetchuck\Robo\Yarn\Tests\AcceptanceTester;
use Sweetchuck\Robo\Yarn\Tests\Helper\RoboFiles\YarnRoboFile;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Sweetchuck\Robo\Yarn\Task\YarnVersionTask
 * @covers \Sweetchuck\Robo\Yarn\Task\BaseCliTask
 * @covers \Sweetchuck\Robo\Yarn\Task\BaseTask
 * @covers \Sweetchuck\Robo\Yarn\YarnTaskLoader
 */
class YarnVersionTaskCest
{
    /**
     * @var string[]
     */
    protected array $tmpDirs = [];

    protected Filesystem $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    public function __destruct()
    {
        $this->fs->remove($this->tmpDirs);
    }

    public function runVersionSuccess(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('01');

        $id = 'version';
        $I->runRoboTask($id, YarnRoboFile::class, 'version:success', $tmpDir);

        $expectedStdOutput = '/^\d+\.\d+/';
        $expectedStdError = sprintf(
            " [Yarn - Version] cd %s && yarn --version\n",
            escapeshellarg($tmpDir)
        );

        $actualExitCode = $I->getRoboTaskExitCode($id);
        $actualStdOutput = $I->getRoboTaskStdOutput($id);
        $actualStdError = $I->getRoboTaskStdError($id);

        $I->assertSame(
            0,
            $actualExitCode,
            'exitCode',
        );

        $I->assertRegExp(
            $expectedStdOutput,
            $actualStdOutput,
            'stdOutput',
        );

        $I->assertStringContainsString(
            $expectedStdError,
            $actualStdError,
            'stdError',
        );
    }

    protected function createTmpDir(string $fixture): string
    {
        $dirName = tempnam(sys_get_temp_dir(), 'robo-yarn.test.');
        $this->fs->remove($dirName);
        $this->fs->mkdir($dirName, 0777 - umask());
        if ($fixture) {
            $this->fs->mirror(
                codecept_data_dir("fixtures/$fixture"),
                $dirName
            );
        }

        $this->tmpDirs[] = $dirName;

        return $dirName;
    }
}
