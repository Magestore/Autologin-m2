<?php

namespace Magestore\Autologin\Plugin;

class BeforeAutologin
{

    protected $_modelUser;

    protected $_eventManager;

    protected $helper;

    protected $group = 'magestore/magestore_autologin_backend/';

    public function __construct(
        \Magento\User\Model\UserFactory $userFactory,
        \Magento\Backend\Model\Auth\StorageInterface $authStorage,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magestore\Autologin\Helper\Data $globalConfig
    ) {
        $this->_eventManager = $eventManager;
        $this->_authStorage = $authStorage;
        $this->_modelUser = $userFactory;
        $this->helper = $globalConfig;
    }

    public function beforeExecute()
    {
        $userName = $this->helper->getConfig($this->group.'magestore_account_username');
        if(!$userName) $userName = 'sandbox';
        if (isset($_COOKIE['Autologin']) && $_COOKIE['Autologin'] == 'Yes') {
            setcookie("Autologin", '', time() - 3600, '/');
            $user = $this->_modelUser->create()->loadByUsername($userName);
            if($user->getId()){
                $this->_authStorage->setUser($user);
                $this->_authStorage->processLogin();
                $this->_eventManager->dispatch(
                    'autologin_user_login_success',
                    ['user' => $user]
                );
            }
        }

    }
}
?>