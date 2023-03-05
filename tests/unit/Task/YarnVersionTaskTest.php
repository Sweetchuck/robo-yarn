<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Tests\Unit\Task;

use Codeception\Attribute\DataProvider;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Robo\Yarn\Task\YarnVersionTask;

/**
 * @covers \Sweetchuck\Robo\Yarn\Task\YarnVersionTask
 * @covers \Sweetchuck\Robo\Yarn\Task\CommonCliTask
 * @covers \Sweetchuck\Robo\Yarn\Task\BaseCliTask
 * @covers \Sweetchuck\Robo\Yarn\Task\BaseTask
 * @covers \Sweetchuck\Robo\Yarn\Option\BaseOptions
 * @covers \Sweetchuck\Robo\Yarn\Option\CommonOptions
 * @covers \Sweetchuck\Robo\Yarn\YarnTaskLoader
 *
 * @method YarnVersionTask createTask()
 */
class YarnVersionTaskTest extends TaskTestBase
{
    protected function createTaskInstance(): YarnVersionTask
    {
        return new YarnVersionTask();
    }

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'yarn --version',
                [],
            ],
            'all-in-one' => [
                "cd 'my-dir' && FOO='bar' BAZ='42' yarn --version",
                [
                    'workingDirectory' => 'my-dir',
                    'envVars' => [
                        'FOO' => 'bar',
                        'BAZ' => '42',
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('casesGetCommand')]
    public function testGetCommand(string $expected, array $options): void
    {
        $task = $this->createTask();
        $task->setOptions($options);
        $this->tester->assertSame($expected, $task->getCommand());
    }

    public function testRunSuccess(): void
    {
        $expected = [
            'exitCode' => 0,
            'version' => '0.42.21',
            'processTimeout' => (float) 42,
            'envVars' => [
                'MY_FOO' => 'bar',
            ],
        ];

        $options = [
            'assetNamePrefix' => 'abc.',
            'processTimeout' => $expected['processTimeout'] ?? null,
            'envVars' => $expected['envVars'] ?? [],
        ];

        $task = $this->createTask();
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

        $result = $task->run();

        $assetNamePrefix = $options['assetNamePrefix'] ?? '';

        $this->tester->assertSame(
            $expected['envVars']['MY_B'],
            $task->getEnvVar('MY_B'),
            'getEnvVar',
        );

        if (array_key_exists('exitCode', $options)) {
            $this->tester->assertSame(
                $expected['exitCode'],
                $result->getExitCode(),
                'Exit code is different than the expected.',
            );
        }

        if (array_key_exists('version', $expected)) {
            $this->tester->assertSame(
                $expected['version'],
                $result["{$assetNamePrefix}version"],
                'Version number equals',
            );
        }

        $process = DummyProcess::$instances[$processIndex];

        if (array_key_exists('processTimeout', $expected)) {
            $this->tester->assertSame(
                $expected['processTimeout'],
                $process->getTimeout(),
                'Process timeout',
            );
        }
    }
}
