<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Tests\Task;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use Sweetchuck\Robo\Yarn\Utils;

class UtilsTest extends Unit
{

    public function casesFindFileUpward(): array
    {
        return [
            'nowhere' => [
                null,
                [
                    'l1' => [
                        'x' => 'a',
                        'l2' => [
                            'y' => 'b',
                        ],
                    ],
                ],
                [
                    'fileName' => '.nvmrc',
                    'currentDir' => 'l1/l2',
                    'rootDir' => '',
                ],
            ],
            'in the current dir' => [
                'l1/l2/.nvmrc',
                [
                    'l1' => [
                        '.nvmrc' => 'a',
                        'l2' => [
                            '.nvmrc' => 'b',
                        ],
                    ],
                ],
                [
                    'fileName' => '.nvmrc',
                    'currentDir' => 'l1/l2',
                    'rootDir' => '',
                ],
            ],
            'in the current dir; same root' => [
                'l1/l2/.nvmrc',
                [
                    'l1' => [
                        '.nvmrc' => 'a',
                        'l2' => [
                            '.nvmrc' => 'b',
                        ],
                    ],
                ],
                [
                    'fileName' => '.nvmrc',
                    'currentDir' => 'l1/l2',
                    'rootDir' => 'l1/l2',
                ],
            ],
            'one dir above' => [
                'l1/.nvmrc',
                [
                    'l1' => [
                        '.nvmrc' => 'a',
                        'l2' => [
                            'x' => 'b',
                        ],
                    ],
                ],
                [
                    'fileName' => '.nvmrc',
                    'currentDir' => 'l1/l2',
                    'rootDir' => '',
                ],
            ],
            'above the root dir' => [
                null,
                [
                    'l1' => [
                        '.nvmrc' => 'a',
                        'l2' => [
                            'x' => 'b',
                            'l3' => [
                                'y' => 'b',
                            ],
                        ],
                    ],
                ],
                [
                    'fileName' => '.nvmrc',
                    'currentDir' => 'l1/l2/l3',
                    'rootDir' => 'l1/l2',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesFindFileUpward
     */
    public function testFindFileUpward(?string $expected, array $fsStructure, array $args): void
    {
        $rootDirName = implode('.', [
            str_replace('\\', '_', static::class),
            $this->getName(false),
            $this->dataName(),
        ]);
        $rootDir = vfsStream::setup($rootDirName, null, $fsStructure);
        $rootDirPath = urldecode($rootDir->url());

        $args += [
            'currentDir' => '.',
            'rootDir' => '',
        ];

        $args['currentDir'] = "$rootDirPath/{$args['currentDir']}";
        if ($args['rootDir']) {
            $args['rootDir'] = "$rootDirPath/{$args['rootDir']}";
        }

        $this->assertSame(
            $expected ? "$rootDirPath/$expected" : $expected,
            Utils::findFileUpward($args['fileName'], $args['currentDir'], $args['rootDir'])
        );
    }
}
