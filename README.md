# Robo task wrapper for Yarn

[![Build Status](https://travis-ci.org/Sweetchuck/robo-yarn.svg?branch=master)](https://travis-ci.org/Sweetchuck/robo-yarn)
[![codecov](https://codecov.io/gh/Sweetchuck/robo-yarn/branch/master/graph/badge.svg)](https://codecov.io/gh/Sweetchuck/robo-yarn)

@todo


## Supported commands

* yarn --version
* yarn install


## Example

```php
<?php

use Sweetchuck\Robo\Yarn\YarnTaskLoader;
use Robo\Contract\TaskInterface;
use Robo\Tasks;

class RoboFile extends Tasks
{
    use YarnTaskLoader;

    public function yarnVersion(string $dir): TaskInterface
    {
        return $this->taskYarnVersion([
            'workingDirectory' => $dir,
        ]);
    }

    public function yarnInstall(string $dir): TaskInterface
    {
        return $this->taskYarnInstall([
            'workingDirectory' => $dir,
        ]);
    }
}
```
