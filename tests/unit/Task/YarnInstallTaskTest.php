<?php

namespace Sweetchuck\Robo\Yarn\Tests\Task;

use Sweetchuck\Robo\Yarn\Task\YarnInstallTask;
use Codeception\Test\Unit;

class YarnInstallTaskTest extends Unit
{
    /**
     * @var \Sweetchuck\Robo\Yarn\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'yarn install',
                [],
            ],
            'workingDirectory' => [
                "cd 'my-dir' && yarn install",
                [
                    'workingDirectory' => 'my-dir',
                ]
            ],
            'nodeExecutable' => [
                'my-node my-yarn install',
                [
                    'nodeExecutable' => 'my-node',
                    'yarnExecutable' => 'my-yarn',
                ]
            ],
            'yarnExecutable' => [
                'my-yarn install',
                [
                    'yarnExecutable' => 'my-yarn',
                ]
            ],
            'verbose' => [
                'yarn install --verbose',
                [
                    'verbose' => true,
                ],
            ],
            'offline' => [
                'yarn install --offline',
                [
                    'offline' => true,
                ],
            ],
            'preferOffline' => [
                'yarn install --prefer-offline',
                [
                    'preferOffline' => true,
                ],
            ],
            'strictSemver' => [
                'yarn install --strict-semver',
                [
                    'strictSemver' => true,
                ],
            ],
            'json' => [
                'yarn install --json',
                [
                    'json' => true,
                ],
            ],
            'ignoreScripts' => [
                'yarn install --ignore-scripts',
                [
                    'ignoreScripts' => true,
                ],
            ],
            'har' => [
                'yarn install --har',
                [
                    'har' => true,
                ],
            ],
            'ignorePlatform' => [
                'yarn install --ignore-platform',
                [
                    'ignorePlatform' => true,
                ],
            ],
            'ignoreEngines' => [
                'yarn install --ignore-engines',
                [
                    'ignoreEngines' => true,
                ],
            ],
            'ignoreOptional' => [
                'yarn install --ignore-optional',
                [
                    'ignoreOptional' => true,
                ],
            ],
            'force' => [
                'yarn install --force',
                [
                    'force' => true,
                ],
            ],
            'noBinLinks' => [
                'yarn install --no-bin-links',
                [
                    'noBinLinks' => true,
                ],
            ],
            'flat' => [
                'yarn install --flat',
                [
                    'flat' => true,
                ],
            ],
            'production' => [
                'yarn install --production',
                [
                    'production' => true,
                ],
            ],
            'noLockFile' => [
                'yarn install --no-lockfile',
                [
                    'noLockFile' => true,
                ],
            ],
            'pureLockFile' => [
                'yarn install --pure-lockfile',
                [
                    'pureLockFile' => true,
                ],
            ],
            'frozenLockFile' => [
                'yarn install --frozen-lockfile',
                [
                    'frozenLockFile' => true,
                ],
            ],
            'globalFolder' => [
                "yarn install --global-folder 'my-dir'",
                [
                    'globalFolder' => 'my-dir',
                ],
            ],
            'modulesFolder' => [
                "yarn install --modules-folder 'my-dir'",
                [
                    'modulesFolder' => 'my-dir',
                ],
            ],
            'cacheFolder' => [
                "yarn install --cache-folder 'my-dir'",
                [
                    'cacheFolder' => 'my-dir',
                ],
            ],
            'mutex' => [
                "yarn install --mutex 'foo:bar'",
                [
                    'mutex' => 'foo:bar',
                ],
            ],
            'noEmoji' => [
                'yarn install --no-emoji',
                [
                    'noEmoji' => true,
                ],
            ],
            'proxy' => [
                "yarn install --proxy 'my-host'",
                [
                    'proxy' => 'my-host',
                ],
            ],
            'httpsProxy' => [
                "yarn install --https-proxy 'my-host'",
                [
                    'httpsProxy' => 'my-host',
                ],
            ],
            'noProgress' => [
                'yarn install --no-progress',
                [
                    'noProgress' => true,
                ],
            ],
            'networkConcurrency' => [
                'yarn install --network-concurrency 42',
                [
                    'networkConcurrency' => 42,
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new YarnInstallTask();
        $task->setOptions($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }

    public function testGetSetSkipIfPackageJsonNotExists(): void
    {
        $task = new YarnInstallTask();
        $this->assertEquals(false, $task->getSkipIfPackageJsonNotExists());
        $task->setOptions(['skipIfPackageJsonNotExists' => true]);
        $this->assertEquals(true, $task->getSkipIfPackageJsonNotExists());
    }
}
