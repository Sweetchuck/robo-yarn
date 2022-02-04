<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Tests\Acceptance\Task;

use Sweetchuck\Robo\Yarn\Tests\AcceptanceTester;
use Sweetchuck\Robo\Yarn\Tests\Helper\RoboFiles\YarnRoboFile;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Sweetchuck\Robo\Yarn\Task\YarnVersionTask<extended>
 * @covers \Sweetchuck\Robo\Yarn\YarnTaskLoader
 */
class YarnVersionTaskCest
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

    public function runVersionSuccess(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('01');

        $id = 'version';
        $I->runRoboTask($id, YarnRoboFile::class, 'version:success', $tmpDir);

        $expectedStdError = sprintf(
            " [Yarn - Version] cd %s && yarn --version\n",
            escapeshellarg($tmpDir)
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
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
