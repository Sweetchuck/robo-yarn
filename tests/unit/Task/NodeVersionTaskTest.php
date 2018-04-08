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
                        'full' => null,
                    ],
                ],
                [
                    'yarn.lock' => '',
                ],
            ],
            'yarn.lock; valid' => [
                [
                    'assets' => [
                        'full' => '8.11.1',
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
            'yarn.lock; valid; assetNamePrefix' => [
                [
                    'assets' => [
                        'package01.full' => '8.11.1-rc1+foo',
                        'package01.major' => 8,
                        'package01.minor' => 11,
                        'package01.patch' => 1,
                        'package01.preReleaseVersion' => 'rc1',
                        'package01.buildMetaData' => 'foo',
                    ],
                ],
                [
                    'yarn.lock' => implode(PHP_EOL, [
                        'node@^8.0:',
                        '  version "8.11.1-rc1+foo"',
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
                        'full' => null,
                    ],
                ],
                [
                    'package-lock.json' => json_encode([
                        'dependencies' => [],
                    ]),
                ],
            ],
            'package-lock.json; valid' => [
                [
                    'assets' => [
                        'full' => '1.2.3',
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
            'priority - yarn.lock' => [
                [
                    'assets' => [
                        'full' => '8.11.1',
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
                    '.nvmrc' => implode(PHP_EOL, [
                        '4.5.6',
                        '',
                    ]),
                ],
            ],
            'priority - package-lock.json' => [
                [
                    'assets' => [
                        'full' => '1.2.3',
                    ],
                ],
                [
                    'yarn.lock' => '',
                    'package-lock.json' => json_encode([
                        'dependencies' => [
                            'node' => [
                                'version' => '1.2.3',
                            ],
                        ],
                    ]),
                    '.nvmrc' => implode(PHP_EOL, [
                        '4.5.6',
                        '',
                    ]),
                ],
            ],
            'priority - nvm' => [
                [
                    'assets' => [
                        'full' => '4.5.6',
                    ],
                ],
                [
                    'yarn.lock' => '',
                    'package-lock.json' => json_encode([
                        'dependencies' => [],
                    ]),
                    '.nvmrc' => implode(PHP_EOL, [
                        '4.5.6',
                        '',
                    ]),
                ],
            ],
            'priority - all of them is empty' => [
                [
                    'assets' => [
                        'full' => null,
                    ],
                ],
                [
                    'yarn.lock' => '',
                    'package-lock.json' => json_encode([
                        'dependencies' => [],
                    ]),
                    '.nvmrc' => PHP_EOL,
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesRunSuccess
     */
    public function testRunSuccess(array $expected, array $fsStructure, array $options = []): void
    {
        $rootDirName = implode('.', [
            str_replace('\\', '_', static::class),
            $this->getName(false),
            $this->dataName(),
        ]);

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
            $this->assertSame($expected['exitCode'], $result->getExitCode());
        }

        if (array_key_exists('assets', $expected)) {
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->assertSame($assetValue, $result[$assetName]);
            }
        }
    }
}
