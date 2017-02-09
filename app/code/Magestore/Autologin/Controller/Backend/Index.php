<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Autologin\Controller\Backend;

use \Magento\Framework\App\Action\Action;

class Index extends Action
{
    protected $_storeManager;

    protected $helper;

    protected $group = 'magestore/magestore_autologin_backend/';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magestore\Autologin\Helper\Data $globalConfig
    ) {
        $this->_storeManager = $storeManager;
        $this->helper = $globalConfig;
        parent::__construct($context);
    }


    public function execute()
    {
        $redirect = $this->_objectManager->create('Magento\Backend\Model\Url')->getUrl('adminhtml/auth/login');
        setcookie("Autologin", 'Yes', time()+3600,'/');  /* expire in 1 hour */
        $_COOKIE['Autologin'] = 'Yes';
        if($_COOKIE['Autologin']){
            $this->_redirect($redirect);
        }
    }

}
