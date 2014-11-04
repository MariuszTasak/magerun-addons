<?php

namespace Nexway\SetupManager\Util\Processor\Action\Website;

use Nexway\SetupManager\Util\Processor\AbstractConfigurationAction;


/**
 * @category     Nexway
 * @package      Nexway_SetupManager
 * @author       Michał Adamiak <madamiak@nexway.com>
 * @copyright    Copyright (c) 2013, Nexway
 */
class Deleteconfig extends AbstractConfigurationAction
{
    public function execute()
    {
        $this->setScope('website');
        return $this->_deleteConfig();
    }
}
