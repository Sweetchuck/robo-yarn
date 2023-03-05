<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Yarn\Tests\Unit\Task;

use Codeception\Test\Unit;
use League\Container\Container as LeagueContainer;
use Psr\Container\ContainerInterface;
use Robo\Application;
use Robo\Collection\CollectionBuilder;
use Robo\Config\Config as RoboConfig;
use Robo\Robo;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyOutput;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcessHelper;
use Sweetchuck\Robo\Yarn\Task\BaseTask;
use Sweetchuck\Robo\Yarn\Tests\UnitTester;
use Sweetchuck\Robo\Yarn\Tests\Helper\Dummy\DummyTaskBuilder;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\ErrorHandler\BufferingLogger;

abstract class TaskTestBase extends Unit
{
    protected ContainerInterface $container;

    protected RoboConfig $config;

    protected CollectionBuilder $builder;

    protected UnitTester $tester;

    protected CollectionBuilder $Builder;

    protected DummyTaskBuilder $taskBuilder;

    /**
     * @inheritdoc
     */
    public function _before()
    {
        parent::_before();

        DummyProcess::reset();

        Robo::unsetContainer();
        $this->container = new LeagueContainer();
        $application = new SymfonyApplication('Sweetchuck - Robo PHPUnit', '3.0.0');
        $application->getHelperSet()->set(new DummyProcessHelper(), 'process');
        $this->config = new RoboConfig();
        $input = null;
        $output = new DummyOutput([
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
        ]);

        $this->container->add('container', $this->container);

        Robo::configureContainer($this->container, $application, $this->config, $input, $output);
        $this->container->add('logger', BufferingLogger::class);

        $this->builder = CollectionBuilder::create($this->container, null);
        $this->taskBuilder = new DummyTaskBuilder();
        $this->taskBuilder->setContainer($this->container);
        $this->taskBuilder->setBuilder($this->builder);
    }

    protected function createTask(): BaseTask
    {
        $container = new LeagueContainer();
        $application = new Application('Sweetchuck - Yarn', '3.0.0');
        $application->getHelperSet()->set(new DummyProcessHelper(), 'process');
        $config = new RoboConfig();
        $output = new DummyOutput([]);
        $loggerOutput = new DummyOutput([]);
        $logger = new ConsoleLogger($loggerOutput);

        $container->add('output', $output);
        $container->add('logger', $logger);
        $container->add('config', $config);
        $container->add('application', $application);

        $task = $this->createTaskInstance();
        $task->setContainer($container);
        $task->setOutput($output);
        $task->setLogger($logger);

        return $task;
    }

    abstract protected function createTaskInstance(): BaseTask;
}
