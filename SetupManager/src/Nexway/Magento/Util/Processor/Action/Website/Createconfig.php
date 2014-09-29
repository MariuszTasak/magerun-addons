<?php

namespace Nexway\Magento\Util\Processor\Action\Website;

use Nexway\Magento\Util\Processor\AbstractConfigurationAction;

/**
 * @category     Nexway
 * @package      Nexway_SetupManager
 * @author       Michał Adamiak <madamiak@nexway.com>
 * @copyright    Copyright (c) 2013, Nexway
 */
class Createconfig extends AbstractConfigurationAction
{
    public function execute()
    {
        $this->setScope('website');
        return $this->_createConfig();
    }
}
