# Robo task wrapper for Yarn

[![CircleCI](https://circleci.com/gh/Sweetchuck/robo-yarn.svg?style=svg)](https://circleci.com/gh/Sweetchuck/robo-yarn)
[![codecov](https://codecov.io/gh/Sweetchuck/robo-yarn/branch/master/graph/badge.svg)](https://codecov.io/gh/Sweetchuck/robo-yarn)


## Supported commands

* yarn --version
* yarn install
* detect the configured NodeJS version from
  * yarn.lock
  * package-lock.json
  * .nvmrc


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
