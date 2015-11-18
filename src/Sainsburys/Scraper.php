<?php

namespace Sainsburys;

use Goutte\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{

    /**
     * @var \Goutte\Client
     */
    protected $client;

    /**
     * @var array
     */
    public $results = [];

    /**
     * @var int
     */
    public $total = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Set the Guzzle HTTP Client
     *
     * @param ClientInterface $client
     */
    public function setHttpClient(ClientInterface $client) {
        $this->client->setClient($client);
    }

    /**
     * Run the Scraper
     *
     * @param $url
     */
    public function scrape($url)
    {
        $crawler = $this->client->request('GET', $url);

        $crawler->filter('#productLister .product')->each(function ($node) {
            $this->_addProduct($node);
        });

        $return = new \stdClass();
        $return->results = $this->results;
        $return->total = $this->total;

        return $return;
    }

    /**
     * Add product data
     *
     * @param Crawler $node
     */
    private function _addProduct(Crawler $node)
    {
        $product = new \stdClass();

        /** @var Crawler $title */
        $title = $node->filter('h3 a');

        //var_dump($title->attr('href'));
        $product->title = trim($title->text());

        $product->unit_price = $this->_getPrice($node->filter('.pricePerUnit'));

        $this->total += $product->unit_price;
        $this->results[] = $product;
    }

    /**
     * Get the price value from a pricePerUnit node
     *
     * @param Crawler $node
     * @return string
     */
    private function _getPrice(Crawler $node)
    {
        $children = '';
        foreach ($node->children() as $child) {
            $children .= $child->nodeValue;
        }
        $text = $node->text();
        $price = mb_substr($text, 0, mb_strpos($text, $children)); // remove /unit
        $price = trim($price, "£ \t\n\r\0\x0B"); // remove whitespace and pound @TODO handle different currencies

        return $price;
    }

}