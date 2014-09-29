<?php

namespace Nexway\Magento\Util\Processor\Action\Store;

use Nexway\Magento\Util\Processor\AbstractConfigurationAction;

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
        $this->_prepare();
        return $this->_deleteConfig();
    }
}
