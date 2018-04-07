<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Tests\Task;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use Robo\Robo;
use Sweetchuck\Robo\Yarn\Task\NodeVersionTask;
use Webmozart\PathUtil\Path;

/**
 * @covers \Sweetchuck\Robo\Yarn\Task\NodeVersionTask
 */
class NodeVersionTaskTest extends Unit
{

    public function casesRunSuccess(): array
    {
        $resolved = '"https://registry.yarnpkg.com/node/-/node-8.11.1.tgz#565fdea7b35f0bf7eebefac6c2b88d34141dd0eb"';

        return [
            'yarn.lock; null' => [
                [
                    'assets' => [
                        'node.version' => null,
                    ],
                ],
                [
                    'yarn.lock' => '',
                ],
            ],
            'yarn.lock; exists' => [
                [
                    'assets' => [
                        'node.version' => '8.11.1',
                    ],
                ],
                [
                    'yarn.lock' => implode(PHP_EOL, [
                        'node@^8.0:',
                        '  version "8.11.1"',
                        "  resolved $resolved",
                        '',
                    ]),
                ],
            ],
            'yarn.lock; exists; asset name prefix' => [
                [
                    'assets' => [
                        'package01.node.version' => '8.11.1',
                    ],
                ],
                [
                    'yarn.lock' => implode(PHP_EOL, [
                        'node@^8.0:',
                        '  version "8.11.1"',
                        "  resolved $resolved",
                        '',
                    ]),
                ],
                [
                    'assetNamePrefix' => 'package01.'
                ],
            ],
            'package-lock.json; null' => [
                [
                    'assets' => [
                        'node.version' => null,
                    ],
                ],
                [
                    'package-lock.json' => json_encode([
                        'dependencies' => [],
                    ]),
                ],
            ],
            'package-lock.json; exists' => [
                [
                    'assets' => [
                        'node.version' => '1.2.3',
                    ],
                ],
                [
                    'package-lock.json' => json_encode([
                        'dependencies' => [
                            'node' => [
                                'version' => '1.2.3',
                            ],
                        ],
                    ]),
                ],
            ],
            'priority' => [
                [
                    'assets' => [
                        'node.version' => '8.11.1',
                    ],
                ],
                [
                    'yarn.lock' => implode(PHP_EOL, [
                        'node@^8.0:',
                        '  version "8.11.1"',
                        "  resolved $resolved",
                        '',
                    ]),
                    'package-lock.json' => json_encode([
                        'dependencies' => [
                            'node' => [
                                'version' => '1.2.3',
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesRunSuccess
     */
    public function testRunSuccess(array $expected, array $fsStructure, array $options = []): void
    {
        $rootDirName = str_replace('\\', '_', static::class) . '.' . $this->getName();
        $rootDir = vfsStream::setup($rootDirName, null, $fsStructure);

        $expected += [
            'exitCode' => 0,
        ];

        $options['workingDirectory'] = Path::join(
            $rootDir->url(),
            $options['workingDirectory'] ?? ''
        );

        $container = Robo::createDefaultContainer();
        Robo::setContainer($container);

        $task = new NodeVersionTask();
        $task->setOptions($options);

        $result = $task->run();

        if (array_key_exists('exitCode', $expected)) {
            $this->assertEquals($expected['exitCode'], $result->getExitCode());
        }

        if (array_key_exists('assets', $expected)) {
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->assertEquals($assetValue, $result[$assetName]);
            }
        }
    }
}
