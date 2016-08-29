<?php

namespace Kraken\Runtime\Supervisor\Cmd;

use Kraken\Channel\ChannelBaseInterface;
use Kraken\Channel\Extra\Request;
use Kraken\Runtime\Supervisor\SolverBase;
use Kraken\Supervisor\SolverInterface;
use Kraken\Runtime\RuntimeCommand;
use Error;
use Exception;

class CmdEscalateManager extends SolverBase implements SolverInterface
{
    /**
     * @var ChannelBaseInterface
     */
    protected $channel;

    /**
     * @var string
     */
    protected $parent;

    /**
     *
     */
    protected function construct()
    {
        $this->channel = $this->runtime->getCore()->make('Kraken\Runtime\Channel\ChannelInterface');
        $this->parent  = $this->runtime->parent();
    }

    /**
     *
     */
    protected function destruct()
    {
        unset($this->channel);
        unset($this->parent);
    }

    /**
     * @param Error|Exception $ex
     * @param mixed[] $params
     * @return mixed
     */
    protected function handler($ex, $params = [])
    {
        $req = $this->createRequest(
            $this->channel,
            $this->parent,
            new RuntimeCommand('cmd:error', [ 'exception' => get_class($ex), 'message' => $ex->getMessage() ])
        );

        return $req->call();
    }

    /**
     * Create Request.
     *
     * @param ChannelBaseInterface $channel
     * @param string $receiver
     * @param string $command
     * @return Request
     */
    protected function createRequest(ChannelBaseInterface $channel, $receiver, $command)
    {
        return new Request($channel, $receiver, $command);
    }
}
