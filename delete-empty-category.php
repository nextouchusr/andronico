<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('global');
$objectManager = Magento\Framework\App\ObjectManager::getInstance();
$categoryFactory = $objectManager->get('Magento\Catalog\Model\CategoryFactory');
$newCategory = $categoryFactory->create();
$collection = $newCategory->getCollection();
$registry = $objectManager->get('\Magento\Framework\Registry');
$registry->register("isSecureArea", true);
foreach($collection as $category) {
    if($category->getId() > 2){
        $category = $objectManager->create('Magento\Catalog\Model\Category')->load($category->getId());
        
        if($category->getProductCollection()->count()==0){
            echo "\r\nDELETE: " . $category->getName() . " " . $category->getProductCollection()->count();
            $category->delete();
        }
    }
}

?>