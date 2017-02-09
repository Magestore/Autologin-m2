<?php
namespace Magestore\Autologin\Controller\Frontend;

use \Magento\Framework\App\Action\Action;

class Index extends Action
{
    protected $helper;

    protected $_objectManager;

    protected $_storeManager;

    protected $group = 'magestore/magestore_autologin_frontend/';

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magestore\Autologin\Helper\Data $globalConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magestore\Autologin\Helper\Data $globalConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->helper = $globalConfig;
        $this->_storeManager = $storeManager;

        parent::__construct($context);
    }

    public function execute()
    {
        $redirect = $this->helper->getConfig($this->group.'magestore_frontend_redirect');
        $websiteId  = $this->_storeManager->getWebsite()->getWebsiteId();
        $email = $this->helper->getConfig($this->group.'magestore_customer_email');
        if($email){
            $customer = $this->_objectManager->create('Magento\Customer\Model\Customer')
                ->setWebsiteId($websiteId)
                ->loadByEmail($email);
            if($customer && $customer->getId()){
                $login = true;
                $this->_objectManager->create('Magento\Customer\Model\Session')->setCustomerAsLoggedIn($customer);
                $this->_objectManager->create('Magento\Customer\Model\Session')->regenerateId();
            }else{
                $login = $this->randomLogin();
            }
        }else{
            $login = $this->randomLogin();
        }
        return ($login) ?  $this->_redirect($redirect) : $this->emptyCustomer($redirect);

    }
    public function randomLogin(){
        $customerCollection = $this->_objectManager->create('Magento\Customer\Model\ResourceModel\Customer\CollectionFactory');
        $firstCustomer = $customerCollection->create()->getFirstItem();
        if($firstCustomer && $firstCustomer->getId()){
            $this->_objectManager->create('Magento\Customer\Model\Session')->setCustomerAsLoggedIn($firstCustomer);
            $this->_objectManager->create('Magento\Customer\Model\Session')->regenerateId();
            return true;
        }else{
            return false;
        }
    }
    public function emptyCustomer($redirect){
        echo __('Not exist any user in website!').'</br>';
        echo __("Redirecting ...");
        if(substr($redirect,0,1) != '/') $redirect = '/'.$redirect;
        return header("Refresh: 000.1; $redirect");
    }

}
