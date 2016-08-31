<?php

namespace Kraken\Runtime\Provider\Console;

use Kraken\Channel\ChannelInterface;
use Kraken\Channel\ChannelCompositeInterface;
use Kraken\Channel\Router\RuleHandler;
use Kraken\Core\CoreInterface;
use Kraken\Core\Service\ServiceProvider;
use Kraken\Core\Service\ServiceProviderInterface;
use Kraken\Runtime\Runtime;

class ConsoleProvider extends ServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string[]
     */
    protected $requires = [
        'Kraken\Config\ConfigInterface',
        'Kraken\Channel\ChannelFactoryInterface',
        'Kraken\Runtime\RuntimeContainerInterface',
        'Kraken\Command\CommandManagerInterface'
    ];

    /**
     * @var string[]
     */
    protected $provides = [
        'Kraken\Runtime\Channel\ConsoleInterface'
    ];

    /**
     * @param CoreInterface $core
     */
    protected function register(CoreInterface $core)
    {
        $config  = $core->make('Kraken\Config\ConfigInterface');
        $factory = $core->make('Kraken\Channel\ChannelFactoryInterface');
        $runtime = $core->make('Kraken\Runtime\RuntimeContainerInterface');

        $console = $factory->create('Kraken\Channel\Channel', [
            $runtime->getParent() === null
                ? $config->get('channel.channels.console.class')
                : 'Kraken\Channel\Model\Null\NullModel',
            array_merge(
                $config->get('channel.channels.console.config'),
                [ 'hosts' => Runtime::RESERVED_CONSOLE_CLIENT ]
            )
        ]);

        $core->instance(
            'Kraken\Runtime\Channel\ConsoleInterface',
            $console
        );
    }

    /**
     * @param CoreInterface $core
     */
    protected function unregister(CoreInterface $core)
    {
        $core->remove(
            'Kraken\Runtime\Channel\ConsoleInterface'
        );
    }

    /**
     * @param CoreInterface $core
     */
    protected function boot(CoreInterface $core)
    {
        $runtime = $core->make('Kraken\Runtime\RuntimeContainerInterface');
        $channel = $core->make('Kraken\Runtime\Channel\ChannelInterface');
        $console = $core->make('Kraken\Runtime\Channel\ConsoleInterface');
        $loop    = $core->make('Kraken\Loop\LoopInterface');

        $this->applyConsoleRouting($channel, $console);

        $runtime->on('create',  function() use($console) {
            $console->start();
        });
        $runtime->on('destroy', function() use($loop, $console) {
            $loop->onTick(function() use($console) {
                $console->stop();
            });
        });
    }

    /**
     * @param ChannelCompositeInterface $channel
     * @param ChannelInterface $console
     */
    private function applyConsoleRouting(ChannelCompositeInterface $channel, ChannelInterface $console)
    {
        $master = $channel->getBus('master');

        $router = $console->getInput();
        $router->addAnchor(
            new RuleHandler(function($params) use($master) {
                $master->receive(
                    $params['alias'],
                    $params['protocol']
                );
            })
        );

        $router = $console->getOutput();
        $router->addAnchor(
            new RuleHandler(function($params) use($channel) {
                $channel->push(
                    $params['alias'],
                    $params['protocol'],
                    $params['flags'],
                    $params['success'],
                    $params['failure'],
                    $params['cancel'],
                    $params['timeout']
                );
            })
        );
    }
}
