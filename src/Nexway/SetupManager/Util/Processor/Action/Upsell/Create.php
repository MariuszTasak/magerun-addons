<?php

namespace Nexway\SetupManager\Util\Processor\Action\Upsell;

use Nexway\SetupManager\Util\Processor\AbstractAction;
/**
 * @category    Nexway
 * @package     Nexway_SetupManager
 * @author      Marcin Tasak <mtasak@nexway.com>
 * @copyright   Copyright (c) 2015, Nexway
 */
class Create extends AbstractAction
{
    protected function _create()
    {
        $this->getParameters()->setModel('nexway_upsell/upsell');

//        $model = $this->getParameters()->getData(self::F_MODEL);
        $data = $this->getParameters()->getData(self::F_DATA);

//        if (!isset($data['asset_target_product_name'])) {
//            $data['asset_target_product_name'] = [];
//        }
//
//        if (!isset($data['asset_target_product_short_description'])) {
//            $data['asset_target_product_short_description'] = [];
//        }


        /** @var Nexway_Upsell_Model_Upsell $upsellModel */
        $upsellModel    = Mage::getModel('nexway_upsell/upsell');
        /** @var Nexway_Upsell_Model_Options $optionsModel */
        $optionsModel   = Mage::getModel('nexway_upsell/options');


        // this is not needed I guess
//        $parsedData = $optionsModel->parseData($data);
//        $upsellModel->setData($parsedData);
//        $data = $parsedData;


//        Mage::helper('nexway_upsell/options')->
//            processMessages($optionsModel->getMessages());

        try {
            $upsellModel->save();
            if (!$upsellModel->getId()) {
                Mage::throwException(Mage::helper('nexway_upsell')->__('Error saving upsell'));
            }

            $id = $upsellModel->getId();
            $upsellModel->setUpsellId($id);

            $optionsModel->setUpsellId($upsellModel->getId());
            $optionsModel->removeOptions($upsellModel->getId());
            $optionsModel->saveIncomingData();

            $product = Mage::helper('nexway_upsell')
                ->getTargetProduct($data['upsell_products']);

            if (isset($data['asset_target_product_name'])) {
                Mage::helper('nexway_assets')->saveAsset(
                    $data['asset_target_product_name'],
                    $product,
                    $upsellModel,
                    'name'
                );
            }
            if (isset($data['asset_target_product_short_description'])) {
                Mage::helper('nexway_assets')->saveAsset(
                    $data['asset_target_product_short_description'],
                    $product,
                    $upsellModel,
                    'short_description'
                );
            }

            /**
             * Upsell will use session messages = automatically errors on
             * page.
             */
            $upsellModel->setMessages($session->getMessages());
            if (!$upsellModel->validateEntity()) {
                $upsellModel->setStatus(
                    Nexway_Upsell_Model_Source_Status::UPSELL_MISCONFIGURED
                );
                /** form data validation requires actual upsell id */
                $upsellModel->setUpsellId($upsellModel->getId());
                $upsellModel->save();

                $session->addNotice(
                    Mage::helper('nexway_upsell')->__('Status has been changed to misconfigured.'
                    ));
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }

            $session->addSuccess(Mage::helper('nexway_upsell')->__('Upsell was successfully saved.'));
            $session->setFormData(FALSE);

            // The following line decides if it is a "save" or "save and continue"
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $id));
            } else {
                $this->_redirect('*/*/');
            }

        } catch (Exception $e) {
            $this->_parseExceptions($e);
            $session->setPageData($data);
            $this->_redirect('*/*/edit', array('id' => $id));
        }

        return;
    }

} 