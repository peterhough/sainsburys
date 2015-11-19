<?php

namespace Sainsburys;

use Goutte\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\BrowserKit\Cookie;
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

        // @FIXME without this Cookie the website serves a JavaScript only version - see known issues
        $this->client->getCookieJar()->set(
            new Cookie(
                'TS017d4e39',
                '01e0f702cf037f97362f62278696546776e7b67bec0aece008837daa4d94f77fe53d7d4c8a6fa39200300641d6aee20da460dd376505f7550bd18461d59d5c2489e7bc515b97917c372e044bb84fb8441a011139863d71d7b41a83ffdab5f7f5dc3492855c362d61bdddc129cdd1d60c4ac9a1b702547433a79abd2e4c7c9a2f9f21e7c748fe9f995fea959736b5aea76fe14d45d2181a39b1f45823787c1cd69e0457f53c'
            )
        );
    }

    /**
     * Set the Guzzle HTTP Client
     *
     * @param ClientInterface $client
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->client->setClient($client);
    }

    /**
     * Run the Scraper
     *
     * @param $url
     */
    public function scrape($url)
    {
        $this->process($url);

        $return = new \stdClass();
        $return->results = $this->results;
        $return->total = $this->total;

        return $return;
    }

    /**
     * Process the URL
     *
     * @param $url
     */
    private function process($url)
    {
        $crawler = $this->client->request('GET', $url);

        $crawler->filter('#productLister .product')->each(function ($node) {
            $this->addProduct($node);
        });

        $nextPage = $crawler->filter('.pages .next a');
        if ($nextPage->count() > 0) {
            $this->process($nextPage->first()->attr('href'));
        }
    }

    /**
     * Add product data
     *
     * @param Crawler $node
     */
    private function addProduct(Crawler $node)
    {
        $product = new \stdClass();

        /** @var Crawler $title */
        $title = $node->filter('h3 a');

        $product->title = trim($title->text());
        $product->unit_price = $this->getPrice($node->filter('.pricePerUnit'));

        $productCrawler = $this->client->request('GET', $title->attr('href'));
        $product->size = $this->bytesToKb(strlen($this->client->getResponse()->getContent()));

        $product->description = '';
        $description = $productCrawler->filterXPath('//h3[.="Description"]');
        if ($description->count() > 0) {
            foreach ($description->siblings() as $sibling) {
                // product pages have different structures!
                if ($sibling->tagName == 'h3') {
                    break;
                }

                if ($product->description != "") {
                    $product->description .= "\n";
                }
                // @TODO address formatting issues - breaks to new lines
                $product->description .= trim(preg_replace("/[^\S\r\n]+/", " ", $sibling->nodeValue)); // remove excess whitespace but not new lines
            }
        }

        $this->total += $product->unit_price; // increment total
        $this->results[] = $product;
    }

    /**
     * Get the price value from a pricePerUnit node
     *
     * @param Crawler $node
     * @return string
     */
    protected function getPrice(Crawler $node)
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

    /**
     * Bytes to KB
     * @TODO Confirm if this should be kilobits or kilobytes - assumed kilobytes
     *
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    protected function bytesToKb($bytes, $decimals = 2)
    {
      return sprintf("%.{$decimals}fkb", $bytes / 1024);
    }
}