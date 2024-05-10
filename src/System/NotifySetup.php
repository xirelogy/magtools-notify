<?php

namespace MagpieTools\Notify\System;

use Magpie\System\Concepts\SystemBootable;
use Magpie\System\Kernel\BootContext;
use Magpie\System\Kernel\BootRegistrar;

/**
 * Setup the framework
 */
class NotifySetup implements SystemBootable
{
    /**
     * @inheritDoc
     */
    public static function systemBootRegister(BootRegistrar $registrar) : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public static function systemBoot(BootContext $context) : void
    {

    }
}