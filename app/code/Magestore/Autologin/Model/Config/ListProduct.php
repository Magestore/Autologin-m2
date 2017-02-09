<?php
namespace Magestore\Autologin\Model\Config;
class ListProduct implements \Magento\Framework\Option\ArrayInterface {

    public function toOptionArray()
    {
        $options = [];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $productCollection->create()
            ->addAttributeToSelect('*')
            ->load();
        foreach ($collection as $product) {
            $options[] = ['value' => $product->getId(), 'label' => __($product->getName())];
        }
        return $options;
    }


}