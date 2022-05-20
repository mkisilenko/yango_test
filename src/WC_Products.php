<?php

/**
 * 
 */
class WC_Products
{
    const BASE_URI = 'https://mediland.co.il/wp-json/wc/v3/';
	private $products = [];

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ErrorException
     */
    public function __construct()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => self::BASE_URI]);
        try {
            $response = $client->request('GET', 'products/', ['auth' => [$_ENV['WP_API_USER'], $_ENV['WP_API_PASS']]]);
            if (200 == $response->getStatusCode()) {
                $body = json_decode((string)$response->getBody());
                if (is_array($body) && count($body) > 0) {
                    $this->products = $body;
                } else {
                    throw new ErrorException('No products');
                }
            }
        } catch (ClientException $exception) {
            die(var_dump($exception->getCode()));
        }
    }

    public function get_products()
    {
        return $this->products;
    }
}