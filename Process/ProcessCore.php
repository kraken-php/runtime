<?php

namespace Kraken\Runtime\Process;

use Kraken\Core\Core;
use Kraken\Core\CoreInterface;
use Kraken\Runtime\Runtime;

class ProcessCore extends Core implements CoreInterface
{
    /**
     * @var string
     */
    const RUNTIME_UNIT = Runtime::UNIT_PROCESS;

    /**
     * @return string[]
     */
    protected function defaultProviders()
    {
        return [
            'Kraken\Core\Provider\Channel\ChannelProvider',
            'Kraken\Core\Provider\Command\CommandProvider',
            'Kraken\Core\Provider\Config\ConfigProvider',
            'Kraken\Core\Provider\Container\ContainerProvider',
            'Kraken\Core\Provider\Core\CoreProvider',
            'Kraken\Core\Provider\Core\EnvironmentProvider',
            'Kraken\Core\Provider\Error\ErrorProvider',
            'Kraken\Core\Provider\Event\EventProvider',
            'Kraken\Core\Provider\Filesystem\FilesystemProvider',
            'Kraken\Core\Provider\Log\LogProvider',
            'Kraken\Core\Provider\Loop\LoopProvider',
            'Kraken\Runtime\Container\Provider\Channel\ChannelProvider',
            'Kraken\Runtime\Container\Provider\Command\CommandProvider',
            'Kraken\Runtime\Container\Provider\Console\ConsoleProvider',
            'Kraken\Runtime\Container\Provider\Error\ErrorProvider',
            'Kraken\Runtime\Container\Provider\Runtime\RuntimeManagerProvider'
        ];
    }

    /**
     * @return string[]
     */
    protected function defaultAliases()
    {
        return [
            'Channel'           => 'Kraken\Runtime\RuntimeChannelInterface',
            'Channel.Internal'  => 'Kraken\Runtime\RuntimeChannelInterface',
            'Channel.Console'   => 'Kraken\Runtime\RuntimeConsoleInterface',
            'CommandManager'    => 'Kraken\Command\CommandManagerInterface',
            'Config'            => 'Kraken\Config\ConfigInterface',
            'Console'           => 'Kraken\Runtime\RuntimeConsoleInterface',
            'Container'         => 'Kraken\Container\ContainerInterface',
            'Core'              => 'Kraken\Core\CoreInterface',
            'Emitter'           => 'Kraken\Event\EventEmitterInterface',
            'Environment'       => 'Kraken\Core\EnvironmentInterface',
            'ErrorManager'      => 'Kraken\Runtime\RuntimeErrorManagerInterface',
            'ErrorSupervisor'   => 'Kraken\Runtime\RuntimeErrorSupervisorInterface',
            'Filesystem'        => 'Kraken\Filesystem\FilesystemInterface',
            'Filesystem.Disk'   => 'Kraken\Filesystem\FilesystemInterface',
            'Filesystem.Cloud'  => 'Kraken\Filesystem\FilesystemManagerInterface',
            'Logger'            => 'Kraken\Log\LoggerInterface',
            'Loop'              => 'Kraken\Loop\LoopInterface'
        ];
    }
}
