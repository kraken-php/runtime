<?php

namespace Kraken\Runtime\Command\Runtimes;

use Kraken\Command\Command;
use Kraken\Command\CommandInterface;
use Kraken\Throwable\Runtime\RejectionException;

class RuntimesDestroyCommand extends Command implements CommandInterface
{
    /**
     * @param mixed[] $params
     * @return mixed
     * @throws RejectionException
     */
    protected function command($params = [])
    {
        if (!isset($params['aliases']) || !isset($params['flags']))
        {
            throw new RejectionException('Invalid params.');
        }

        return $this->runtime->manager()->destroyRuntimes($params['aliases'], (int)$params['flags']);
    }
}
