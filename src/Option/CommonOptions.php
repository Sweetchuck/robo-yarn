<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Option;

trait CommonOptions
{
    // region Option - verbose.
    /**
     * @var bool
     */
    protected $verbose = false;

    public function getVerbose(): bool
    {
        return $this->verbose;
    }

    /**
     * @return $this
     */
    public function setVerbose(bool $value)
    {
        $this->verbose = $value;

        return $this;
    }
    // endregion

    // region Option - offline.
    /**
     * @var bool
     */
    protected $offline = false;

    public function getOffline(): bool
    {
        return $this->offline;
    }

    /**
     * @return $this
     */
    public function setOffline(bool $value)
    {
        $this->offline = $value;

        return $this;
    }
    // endregion

    // region Option - preferOffline.
    /**
     * @var bool
     */
    protected $preferOffline = false;

    public function getPreferOffline(): bool
    {
        return $this->preferOffline;
    }

    /**
     * @return $this
     */
    public function setPreferOffline(bool $value)
    {
        $this->preferOffline = $value;

        return $this;
    }
    // endregion

    // region Option - strictSemver.
    /**
     * @var bool
     */
    protected $strictSemver = false;

    public function getStrictSemver(): bool
    {
        return $this->strictSemver;
    }

    /**
     * @return $this
     */
    public function setStrictSemver(bool $value)
    {
        $this->strictSemver = $value;

        return $this;
    }
    // endregion

    // region Option - json.
    /**
     * @var bool
     */
    protected $json = false;

    public function getJson(): bool
    {
        return $this->json;
    }

    /**
     * @return $this
     */
    public function setJson(bool $value)
    {
        $this->json = $value;

        return $this;
    }
    // endregion

    // region Option - ignoreScripts.
    /**
     * @var bool
     */
    protected $ignoreScripts = false;

    public function getIgnoreScripts(): bool
    {
        return $this->ignoreScripts;
    }

    /**
     * @return $this
     */
    public function setIgnoreScripts(bool $value)
    {
        $this->ignoreScripts = $value;

        return $this;
    }
    // endregion

    // region Option - har.
    /**
     * @var bool
     */
    protected $har = false;

    public function getHar(): bool
    {
        return $this->har;
    }

    /**
     * @return $this
     */
    public function setHar(bool $value)
    {
        $this->har = $value;

        return $this;
    }
    // endregion

    // region Option - ignorePlatform.
    /**
     * @var bool
     */
    protected $ignorePlatform = false;

    public function getIgnorePlatform(): bool
    {
        return $this->ignorePlatform;
    }

    /**
     * @return $this
     */
    public function setIgnorePlatform(bool $value)
    {
        $this->ignorePlatform = $value;

        return $this;
    }
    // endregion

    // region Option - ignoreEngines.
    /**
     * @var bool
     */
    protected $ignoreEngines = false;

    public function getIgnoreEngines(): bool
    {
        return $this->ignoreEngines;
    }

    /**
     * @return $this
     */
    public function setIgnoreEngines(bool $value)
    {
        $this->ignoreEngines = $value;

        return $this;
    }
    // endregion

    // region Option - ignoreOptional.
    /**
     * @var bool
     */
    protected $ignoreOptional = false;

    public function getIgnoreOptional(): bool
    {
        return $this->ignoreOptional;
    }

    /**
     * @return $this
     */
    public function setIgnoreOptional(bool $value)
    {
        $this->ignoreOptional = $value;

        return $this;
    }
    // endregion

    // region Option - force.
    /**
     * @var bool
     */
    protected $force = false;

    public function getForce(): bool
    {
        return $this->force;
    }

    /**
     * @return $this
     */
    public function setForce(bool $value)
    {
        $this->force = $value;

        return $this;
    }
    // endregion

    // region Option - noBinLinks.
    /**
     * @var bool
     */
    protected $noBinLinks = false;

    public function getNoBinLinks(): bool
    {
        return $this->noBinLinks;
    }

    /**
     * @return $this
     */
    public function setNoBinLinks(bool $value)
    {
        $this->noBinLinks = $value;

        return $this;
    }
    // endregion

    // region Option - flat.
    /**
     * @var bool
     */
    protected $flat = false;

    public function getFlat(): bool
    {
        return $this->flat;
    }

    /**
     * @return $this
     */
    public function setFlat(bool $value)
    {
        $this->flat = $value;

        return $this;
    }
    // endregion

    // region Option - production.
    /**
     * @var bool
     */
    protected $production = false;

    public function getProduction(): bool
    {
        return $this->production;
    }

    /**
     * @return $this
     */
    public function setProduction(bool $value)
    {
        $this->production = $value;

        return $this;
    }
    // endregion

    // region Option - noLockFile.
    /**
     * @var bool
     */
    protected $noLockFile = false;

    public function getNoLockFile(): bool
    {
        return $this->noLockFile;
    }

    /**
     * @return $this
     */
    public function setNoLockFile(bool $value)
    {
        $this->noLockFile = $value;

        return $this;
    }
    // endregion

    // region Option - pureLockFile.
    /**
     * @var bool
     */
    protected $pureLockFile = false;

    public function getPureLockFile(): bool
    {
        return $this->pureLockFile;
    }

    /**
     * @return $this
     */
    public function setPureLockFile(bool $value)
    {
        $this->pureLockFile = $value;

        return $this;
    }
    // endregion

    // region Option - frozenLockFile.
    /**
     * @var bool
     */
    protected $frozenLockFile = false;

    public function getFrozenLockFile(): bool
    {
        return $this->frozenLockFile;
    }

    /**
     * @return $this
     */
    public function setFrozenLockFile(bool $value)
    {
        $this->frozenLockFile = $value;

        return $this;
    }
    // endregion

    // region Option - globalFolder.
    /**
     * @var string
     */
    protected $globalFolder = '';

    public function getGlobalFolder(): string
    {
        return $this->globalFolder;
    }

    /**
     * @return $this
     */
    public function setGlobalFolder(string $value)
    {
        $this->globalFolder = $value;

        return $this;
    }
    // endregion

    // region Option - modulesFolder.
    /**
     * @var string
     */
    protected $modulesFolder = '';

    public function getModulesFolder(): string
    {
        return $this->modulesFolder;
    }

    /**
     * @return $this
     */
    public function setModulesFolder(string $value)
    {
        $this->modulesFolder = $value;

        return $this;
    }
    // endregion

    // region Option - cacheFolder.
    /**
     * @var string
     */
    protected $cacheFolder = '';

    public function getCacheFolder(): string
    {
        return $this->cacheFolder;
    }

    /**
     * @return $this
     */
    public function setCacheFolder(string $value)
    {
        $this->cacheFolder = $value;

        return $this;
    }
    // endregion

    // region Option - mutex.
    /**
     * @var string
     */
    protected $mutex = '';

    public function getMutex(): string
    {
        return $this->mutex;
    }

    /**
     * @return $this
     */
    public function setMutex(string $value)
    {
        $this->mutex = $value;

        return $this;
    }
    // endregion

    // region Option - noEmoji.
    /**
     * @var bool
     */
    protected $noEmoji = false;

    public function getNoEmoji(): bool
    {
        return $this->noEmoji;
    }

    /**
     * @return $this
     */
    public function setNoEmoji(bool $value)
    {
        $this->noEmoji = $value;

        return $this;
    }
    // endregion

    // region Option - proxy.
    /**
     * @var string
     */
    protected $proxy = '';

    public function getProxy(): string
    {
        return $this->proxy;
    }

    /**
     * @return $this
     */
    public function setProxy(string $value)
    {
        $this->proxy = $value;

        return $this;
    }
    // endregion

    // region Option - httpsProxy.
    /**
     * @var string
     */
    protected $httpsProxy = '';

    public function getHttpsProxy(): string
    {
        return $this->httpsProxy;
    }

    /**
     * @return $this
     */
    public function setHttpsProxy(string $value)
    {
        $this->httpsProxy = $value;

        return $this;
    }
    // endregion

    // region Option - noProgress.
    /**
     * @var bool
     */
    protected $noProgress = false;

    public function getNoProgress(): bool
    {
        return $this->noProgress;
    }

    /**
     * @return $this
     */
    public function setNoProgress(bool $value)
    {
        $this->noProgress = $value;

        return $this;
    }
    // endregion

    // region Option - networkConcurrency.
    /**
     * @var int
     */
    protected $networkConcurrency = 0;

    public function getNetworkConcurrency(): int
    {
        return $this->networkConcurrency;
    }

    /**
     * @return $this
     */
    public function setNetworkConcurrency(int $value)
    {
        $this->networkConcurrency = $value;

        return $this;
    }
    // endregion

    protected function getOptionsCommon(): array
    {
        return [
            'verbose' => [
                'type' => 'flag',
                'value' => $this->getVerbose(),
            ],
            'offline' => [
                'type' => 'flag',
                'value' => $this->getOffline(),
            ],
            'prefer-offline' => [
                'type' => 'flag',
                'value' => $this->getPreferOffline(),
            ],
            'strict-semver' => [
                'type' => 'flag',
                'value' => $this->getStrictSemver(),
            ],
            'json' => [
                'type' => 'flag',
                'value' => $this->getJson(),
            ],
            'ignore-scripts' => [
                'type' => 'flag',
                'value' => $this->getIgnoreScripts(),
            ],
            'har' => [
                'type' => 'flag',
                'value' => $this->getHar(),
            ],
            'ignore-platform' => [
                'type' => 'flag',
                'value' => $this->getIgnorePlatform(),
            ],
            'ignore-engines' => [
                'type' => 'flag',
                'value' => $this->getIgnoreEngines(),
            ],
            'ignore-optional' => [
                'type' => 'flag',
                'value' => $this->getIgnoreOptional(),
            ],
            'force' => [
                'type' => 'flag',
                'value' => $this->getForce(),
            ],
            'no-bin-links' => [
                'type' => 'flag',
                'value' => $this->getNoBinLinks(),
            ],
            'flat' => [
                'type' => 'flag',
                'value' => $this->getFlat(),
            ],
            'production' => [
                'type' => 'flag',
                'value' => $this->getProduction(),
            ],
            'no-lockfile' => [
                'type' => 'flag',
                'value' => $this->getNoLockFile(),
            ],
            'pure-lockfile' => [
                'type' => 'flag',
                'value' => $this->getPureLockFile(),
            ],
            'frozen-lockfile' => [
                'type' => 'flag',
                'value' => $this->getFrozenLockFile(),
            ],
            'global-folder' => [
                'type' => 'value',
                'value' => $this->getGlobalFolder(),
            ],
            'modules-folder' => [
                'type' => 'value',
                'value' => $this->getModulesFolder(),
            ],
            'cache-folder' => [
                'type' => 'value',
                'value' => $this->getCacheFolder(),
            ],
            'mutex' => [
                'type' => 'value',
                'value' => $this->getMutex(),
            ],
            'no-emoji' => [
                'type' => 'flag',
                'value' => $this->getNoEmoji(),
            ],
            'proxy' => [
                'type' => 'value',
                'value' => $this->getProxy(),
            ],
            'https-proxy' => [
                'type' => 'value',
                'value' => $this->getHttpsProxy(),
            ],
            'no-progress' => [
                'type' => 'flag',
                'value' => $this->getNoProgress(),
            ],
            'network-concurrency' => [
                'type' => 'int',
                'value' => $this->getNetworkConcurrency(),
            ],
        ];
    }

    /**
     * @return $this
     */
    protected function setOptionsCommon(array $options)
    {
        foreach ($options as $name => $value) {
            switch ($name) {
                case 'verbose':
                    $this->setVerbose($value);
                    break;

                case 'offline':
                    $this->setOffline($value);
                    break;

                case 'preferOffline':
                    $this->setPreferOffline($value);
                    break;

                case 'strictSemver':
                    $this->setStrictSemver($value);
                    break;

                case 'json':
                    $this->setJson($value);
                    break;

                case 'ignoreScripts':
                    $this->setIgnoreScripts($value);
                    break;

                case 'har':
                    $this->setHar($value);
                    break;

                case 'ignorePlatform':
                    $this->setIgnorePlatform($value);
                    break;

                case 'ignoreEngines':
                    $this->setIgnoreEngines($value);
                    break;

                case 'ignoreOptional':
                    $this->setIgnoreOptional($value);
                    break;

                case 'force':
                    $this->setForce($value);
                    break;

                case 'noBinLinks':
                    $this->setNoBinLinks($value);
                    break;

                case 'flat':
                    $this->setFlat($value);
                    break;

                case 'production':
                    $this->setProduction($value);
                    break;

                case 'noLockFile':
                    $this->setNoLockFile($value);
                    break;

                case 'pureLockFile':
                    $this->setPureLockFile($value);
                    break;

                case 'frozenLockFile':
                    $this->setFrozenLockFile($value);
                    break;

                case 'globalFolder':
                    $this->setGlobalFolder($value);
                    break;

                case 'modulesFolder':
                    $this->setModulesFolder($value);
                    break;

                case 'cacheFolder':
                    $this->setCacheFolder($value);
                    break;

                case 'mutex':
                    $this->setMutex($value);
                    break;

                case 'noEmoji':
                    $this->setNoEmoji($value);
                    break;

                case 'proxy':
                    $this->setProxy($value);
                    break;

                case 'httpsProxy':
                    $this->setHttpsProxy($value);
                    break;

                case 'noProgress':
                    $this->setNoProgress($value);
                    break;

                case 'networkConcurrency':
                    $this->setNetworkConcurrency($value);
                    break;
            }
        }

        return $this;
    }
}
