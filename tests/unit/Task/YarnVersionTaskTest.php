<?php

namespace Sweetchuck\Robo\Yarn\Tests\Task;

use Sweetchuck\Robo\Yarn\Task\YarnVersionTask;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Robo\Robo;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyOutput;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;

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
        $task = new YarnVersionTask();
        $task->setOptions($options);
        $this->tester->assertSame($expected, $task->getCommand());
    }

    public function testRunSuccess(): void
    {
        $expected['exitCode'] = 0;
        $expected['version'] = '0.42.21';
        $expected['processTimeout'] = (float) 42;
        $expected['envVars'] = [
            'MY_FOO' => 'bar',
        ];

        $backupContainer = Robo::hasContainer() ? Robo::getContainer() : null;
        $container = Robo::createDefaultContainer();
        Robo::setContainer($container);

        $mainStdOutput = new DummyOutput([]);

        $options = [
            'assetNamePrefix' => 'abc.',
            'processTimeout' => $expected['processTimeout'] ?? null,
            'envVars' => $expected['envVars'] ?? [],
        ];

        /** @var \Sweetchuck\Robo\Yarn\Task\YarnVersionTask $task */
        $task = Stub::construct(
            YarnVersionTask::class,
            [],
            [
                'processClass' => DummyProcess::class,
            ]
        );
        $task->setOptions($options);

        $task->setEnvVar('MY_B', 'b');
        $expected['envVars']['MY_B'] = 'b';

        $processIndex = count(DummyProcess::$instances);

        DummyProcess::$prophecy[$processIndex] = [
            'exitCode' => 0,
            'stdOutput' => implode("\n", [
                $expected['version'],
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

        $this->tester->assertSame(
            $expected['envVars']['MY_B'],
            $task->getEnvVar('MY_B'),
            'getEnvVar'
        );

        if (array_key_exists('exitCode', $options)) {
            $this->tester->assertSame(
                $expected['exitCode'],
                $result->getExitCode(),
                'Exit code is different than the expected.'
            );
        }

        if (array_key_exists('version', $expected)) {
            $this->tester->assertSame(
                $expected['version'],
                $result["{$assetNamePrefix}version"],
                'Version number equals'
            );
        }

        $process = DummyProcess::$instances[$processIndex];

        if (array_key_exists('envVars', $expected)) {
            $this->tester->assertSame(
                $expected['envVars'],
                $process->getEnv(),
                'Environment variables'
            );
        }

        if (array_key_exists('processTimeout', $expected)) {
            $this->tester->assertSame(
                $expected['processTimeout'],
                $process->getTimeout(),
                'Process timeout'
            );
        }
    }
}
