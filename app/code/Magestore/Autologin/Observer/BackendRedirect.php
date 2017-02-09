<?php

namespace Magestore\Autologin\Observer;


use Magento\Framework\Event\ObserverInterface;


class BackendRedirect implements ObserverInterface
{

    protected $group = 'magestore/magestore_autologin_backend/';

    protected $helper;

    protected $_modelUrl;

    protected $redirect;

    protected $response;

    public function __construct(
        \Magestore\Autologin\Helper\Data $globalConfig,
        \Magento\Backend\Model\UrlFactory $url
    )
    {
        $this->helper = $globalConfig;
        $this->_modelUrl = $url;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $redirect = $this->helper->getConfig($this->group.'magestore_backend_redirect');
        $redirect =   $this->_modelUrl->create()->getUrl($redirect);
        header("Location:$redirect");
        die();
    }

}
