<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Codeception\Util\Fixtures;

class ScraperTest extends \Codeception\TestCase\Test
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Sainsburys\Scraper
     */
    protected $scraper;

    /**
     * @var array
     */
    protected $history = [];

    protected function _before()
    {
        $this->scraper = new \Sainsburys\Scraper();
    }

    protected function _after()
    {
    }

    /**
     * Test Class Construct
     */
    public function testCanConstruct()
    {
        $this->assertInstanceOf('\Sainsburys\Scraper', $this->scraper);
    }

    /**
     * Scrape Ripe and Ready
     */
    public function testCanScrapeRipeAndReady() {
        $this->scraper->setHttpClient($this->getHttpClient(
            [Fixtures::get('ripeAndReady')]
        ));

        $output = $this->scraper->scrape('http://www.sainsburys.mock/shop/gb/groceries/fruit-veg/ripe---ready');

        $this->assertInternalType('object', $output, "Scrape must return an object");
        $this->assertInternalType('array', $output->results, "Scrape must an array of results");
        $this->assertEquals(14, count($output->results), "The results contains the correct number of products");
        $this->assertEquals("29.85", $output->total, "The total unit price is correct");

        $this->assertEquals("Sainsbury's Avocado Ripe & Ready XL Loose 300g", $output->results[0]->title, "The product title is correct");
        $this->assertEquals("1.50", $output->results[0]->unit_price, "The product unit price is correct");
        $this->assertEquals("12.7kb", $output->results[0]->size, "The product HTML page size is correct");
        $this->assertEquals("Avocados", $output->results[0]->description, "The product description is correct");
    }

    /**
     * Scrape a category with pages
     */
    public function testCanScrapePaginatedPage() {
        $this->scraper->setHttpClient($this->getHttpClient(
            [Fixtures::get('aleAndStout')]
        ));

        $output = $this->scraper->scrape('http://www.sainsburys.mock/shop/gb/groceries/drinks/ale-stout');

        $this->assertInternalType('object', $output, "Scrape must return an object");
        $this->assertInternalType('array', $output->results, "Scrape must an array of results");
        $this->assertEquals(136, count($output->results), "The results contains the correct number of products");
        $this->assertEquals("29.85", $output->total, "The total unit price is correct");

        $productDesc = "Beer\n\nBroadside is brewed to commemorate the Battle of Sole Bay (1672). This dark ruby red beer is full of fruitcake flavours and is great savoured with some strong cheddar.\n\nThis bottle contains: 239KJ / 57kcal per 100ml";

        $this->assertEquals("Adnams Broadside Ale 500ml", $output->results[0]->title, "The product title is correct");
        $this->assertEquals("2.00", $output->results[0]->unit_price, "The product unit price is correct");
        $this->assertEquals("13.3kb", $output->results[0]->size, "The product HTML page size is correct");
        $this->assertEquals($productDesc, $output->results[0]->description, "The product description is correct");
    }

    /**
     * Get the mock HTTP client
     *
     * @param array $responses
     * @return GuzzleClient
     */
    protected function getHttpClient(array $responses = [])
    {
        $this->history = [];
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(Middleware::history($this->history));
        $guzzle = new GuzzleClient(array(
            'redirect.disable' => true,
            'base_uri'         => '',
            'handler'          => $handlerStack
        ));

        return $guzzle;
    }
}