<?php

namespace Sweetchuck\Robo\Yarn\Tests\Task;

use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Robo\Yarn\Tests\Unit\Task\TaskTestBase;

/**
 * @covers \Sweetchuck\Robo\Yarn\Task\YarnVersionTask<extended>
 */
class YarnVersionTaskTest extends TaskTestBase
{
    /**
     * @var \Sweetchuck\Robo\Yarn\Task\YarnVersionTask
     */
    protected $task;

    /**
     * @inheritDoc
     */
    protected function initTask()
    {
        $this->task = $this->taskBuilder->taskYarnVersion();
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

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $this->task->setOptions($options);
        $this->tester->assertSame($expected, $this->task->getCommand());
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

        $this->task->setOptions($options);

        $this->task->setEnvVar('MY_B', 'b');
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

        $result = $this->task->run();

        $assetNamePrefix = $options['assetNamePrefix'] ?? '';

        $this->tester->assertSame(
            $expected['envVars']['MY_B'],
            $this->task->getEnvVar('MY_B'),
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

        if (array_key_exists('processTimeout', $expected)) {
            $this->tester->assertSame(
                $expected['processTimeout'],
                $process->getTimeout(),
                'Process timeout'
            );
        }
    }
}
