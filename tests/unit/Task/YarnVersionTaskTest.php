<?php

namespace Sweetchuck\Robo\Yarn\Tests\Task;

use Sweetchuck\AssetJar\AssetJar;
use Sweetchuck\Robo\Yarn\Task\YarnVersionTask;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Robo\Robo;
use Sweetchuck\Robo\Yarn\Test\Helper\Dummy\Output as DummyOutput;
use Sweetchuck\Robo\Yarn\Test\Helper\Dummy\Process as DummyProcess;

class YarnVersionTaskTest extends Unit
{
    /**
     * @var \Sweetchuck\Robo\Yarn\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'yarn --version',
                [],
            ],
            'workingDirectory' => [
                "cd 'my-dir' && yarn --version",
                [
                    'workingDirectory' => 'my-dir',
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new YarnVersionTask($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }

    public function testRunSuccess(): void
    {
        $expectedExitCode = 0;
        $expectedVersion = '0.42.21';

        $backupContainer = Robo::hasContainer() ? Robo::getContainer() : null;
        $container = Robo::createDefaultContainer();
        Robo::setContainer($container);

        $mainStdOutput = new DummyOutput([]);

        $options = [
            'assetNamePrefix' => 'abc.',
        ];

        /** @var \Sweetchuck\Robo\Yarn\Task\YarnVersionTask $task */
        $task = Stub::construct(
            YarnVersionTask::class,
            [$options, []],
            [
                'processClass' => DummyProcess::class,
            ]
        );

        $processIndex = count(DummyProcess::$instances);

        DummyProcess::$prophecy[$processIndex] = [
            'exitCode' => 0,
            'stdOutput' => implode("\n", [
                $expectedVersion,
                '',
            ]),
            'stdError' => '',
        ];

        $task->setLogger($container->get('logger'));
        $task->setOutput($mainStdOutput);

        $result = $task->run();

        if ($backupContainer) {
            Robo::setContainer($backupContainer);
        } else {
            Robo::unsetContainer();
        }

        $assetNamePrefix = $options['assetNamePrefix'] ?? '';

        $this->tester->assertEquals(
            $expectedExitCode,
            $result->getExitCode(),
            'Exit code is different than the expected.'
        );

        $this->tester->assertEquals(
            $expectedVersion,
            $result["{$assetNamePrefix}version"],
            'Version number equals'
        );
    }
}
