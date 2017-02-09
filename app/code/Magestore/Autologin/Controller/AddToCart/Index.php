<?php
namespace Magestore\Autologin\Controller\AddToCart;
use \Magento\Framework\App\Action\Action;

class Index extends Action{

    protected $_storeManager;

    protected $helper;

    protected $group = 'magestore/magestore_autoadd_to_cart/';

    protected $cart;

    protected $product;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magestore\Autologin\Helper\Data $globalConfig,
        \Magento\Catalog\Model\Product $product,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->_storeManager = $storeManager;
        $this->helper = $globalConfig;
        $this->cart = $cart;
        $this->product = $product;
        parent::__construct($context);
    }

    public function execute()
    {
        $redirect = $this->helper->getConfig($this->group.'magestore_autoadd_redirect');
        $idsProduct = $this->helper->getConfig($this->group.'magestore_id_product');
        $idsProduct = explode(',',$idsProduct);
        if($idsProduct){
            foreach($idsProduct as $id){
                $params['qty'] = 1;
                $_product = $this->product->load($id);
                if ($_product) {
                    $this->cart->addProduct($_product, $params);
                }
            }
            $this->cart->save();
        }
        return $this->_redirect($redirect);
    }
}
