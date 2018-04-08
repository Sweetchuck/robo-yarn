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

    public function runInstallWithPackageJsonRequired(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('01');

        $id = __FUNCTION__;
        $I->wantTo('Run "yarn install" with "package.json"; required');
        $I->runRoboTask(
            $id,
            YarnRoboFile::class,
            'install',
            $tmpDir
        );

        $expectedStdOutput = '';
        $expectedStdError = sprintf(
            " [Yarn - Install] cd %s && yarn install\n",
            escapeshellarg($tmpDir)
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertFileExists("$tmpDir/node_modules");
    }

    public function runInstallWithPackageJsonOptional(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('01');

        $id = __FUNCTION__;
        $I->wantTo('Run "yarn install" with "package.json"; optional');
        $I->runRoboTask(
            $id,
            YarnRoboFile::class,
            'install',
            $tmpDir,
            '--skipIfPackageJsonNotExists'
        );

        $expectedStdOutput = '';
        $expectedStdError = sprintf(
            " [Yarn - Install] cd %s && yarn install\n",
            escapeshellarg($tmpDir)
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertFileExists("$tmpDir/node_modules");
    }

    public function runInstallWithoutPackageJsonRequired(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('');

        $id = __FUNCTION__;
        $I->wantTo('Run "yarn install" without "package.json"; required');
        $I->runRoboTask(
            $id,
            YarnRoboFile::class,
            'install',
            $tmpDir
        );

        $expectedStdOutput = '';
        $expectedStdError = sprintf(
            " [Yarn - Install] cd %s && yarn install\n",
            escapeshellarg($tmpDir)
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertFileNotExists("$tmpDir/node_modules");
    }

    public function runInstallWithoutPackageJsonOptional(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir('');

        $id = __FUNCTION__;
        $I->wantTo('Run "yarn install" without "package.json"; optional');
        $I->runRoboTask(
            $id,
            YarnRoboFile::class,
            'install',
            $tmpDir,
            '--skipIfPackageJsonNotExists'
        );

        $expectedStdOutput = '';
        $expectedStdError = sprintf(
            " [Yarn - Install] Skip \"yarn install\" in \"%s\"\n",
            $tmpDir
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertFileNotExists("$tmpDir/node_modules");
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
