<?php

require_once 'vendor/autoload.php';
require_once 'src/WC_Products.php';
require_once 'src/Yango_Shop.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$wc_products = new WC_Products();

$processed_products = [];
foreach ($wc_products->get_products() as $wc_product) {
    $processed_images = Yango_Shop::upload_image($wc_product->images);
    $result = Yango_Shop::add_product($processed_images, $wc_product);
    if ($result !== false) {
        $processed_products[] = $result;
    }
}

echo implode('<br>', $processed_products);