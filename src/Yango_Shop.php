<?php

class Yango_Shop
{
    const BASE_URI = "https://seller.yango.yandex.com/api/shop/";
    public static function upload_image($images) {
        $processed = [];
        foreach ($images as $img) {
            $client = new \GuzzleHttp\Client(['base_uri' => self::BASE_URI]);
            $request = $client->request('POST', $_ENV['YANGO_STORE_ID'] . '/catalog/upload-picture', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $_ENV['YANDEX_TOKEN'],
                    'Content-Type' => 'application/octet-stream',
                    'Accept' => 'application/json, text/plain, */*'
                ],
                'body' => file_get_contents($img->src)
            ]);
            if (200 == $request->getStatusCode()) {
                $processed[] = json_decode((string)$request->getBody());
            }
        }
        return $processed;
    }

    public static function add_product($image, $original_product) {
        $length = !empty($original_product->dimensions->length) ? $original_product->dimensions->length : 0;
        $width = !empty($original_product->dimensions->width) ? $original_product->dimensions->width : 0;
        $height = !empty($original_product->dimensions->height) ? $original_product->dimensions->height : 0;
        $product = new stdClass();
        $product->name = $original_product->name;
        $product->description = strip_tags($original_product->description);
        $product->price = $original_product->price;
        $product->available = $original_product->purchasable;
        $product->isAdult = false;
        $product->weight = !empty($original_product->weight) ? $original_product->weight : 0;
        $product->volume = $length * $height * $width;
        $product->pieces = 0;
        $product->measureType = "weight";
        // Category Temporary hardcoded
        $product->category = [
            'id' => 994008763,
            'name' => 'test cat'
        ];
        $product->pictures = $image;
        $client = new \GuzzleHttp\Client(['base_uri' => self::BASE_URI]);
        $request = $client->request('POST', $_ENV['YANGO_STORE_ID'] . '/catalog/catalogItem',
            [
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['YANDEX_TOKEN'],
            ],
            'json' => $product
        ]);
        if (200 == $request->getStatusCode()) {
            $response = json_decode((string)$request->getBody());
            return $response->name;
        }
        return false;
    }
}