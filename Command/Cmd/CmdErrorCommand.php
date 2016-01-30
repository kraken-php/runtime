<?php

namespace Kraken\Runtime\Command\Cmd;

use Exception;
use Kraken\Command\Command;
use Kraken\Command\CommandInterface;
use Kraken\Error\ErrorManagerInterface;
use Kraken\Exception\Runtime\RejectionException;

class CmdErrorCommand extends Command implements CommandInterface
{
    /**
     * @var ErrorManagerInterface
     */
    protected $manager;

    /**
     *
     */
    protected function construct()
    {
        $this->manager = $this->runtime->core()->make('Kraken\Runtime\RuntimeErrorSupervisorInterface');
    }

    /**
     *
     */
    protected function destruct()
    {
        unset($this->manager);
    }

    /**
     * @param mixed[] $params
     * @return mixed
     * @throws RejectionException
     */
    protected function command($params = [])
    {
        if (!isset($params['exception']) || !isset($params['message']) || !isset($params['origin']))
        {
            throw new RejectionException('Invalid params.');
        }

        $class = $params['exception'];
        $message = $params['message'];
        $origin = $params['origin'];

        try
        {
            throw new $class($message);
        }
        catch (Exception $ex)
        {
            $this->handleException($origin, $ex);
        }
    }

    /**
     * @param string $origin
     * @param Exception $ex
     */
    protected function handleException($origin, Exception $ex)
    {
        try
        {
            $this->manager->handle($ex, [ 'origin' => $origin ]);
        }
        catch (Exception $ex)
        {
            $this->manager->handle($ex, [ 'origin' => $origin ]);
        }
    }
}
