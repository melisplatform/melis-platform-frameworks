<?php

$container = new \Zend\Session\Container('melisinstaller');
if(!empty($container['is_multi_fw'])){
    if($container['is_multi_fw']){
        $frameworkName = $container['framework_name'];
        $result = MelisPlatformFrameworks\Support\MelisPlatformFrameworks::downloadFrameworkSkeleton($frameworkName);
        print $result;
    }
}