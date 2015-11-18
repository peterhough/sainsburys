<?php
require __DIR__ . '/vendor/autoload.php';

$url = isset($argv[1]) ? $argv[1] : 'http://www.sainsburys.co.uk/shop/gb/groceries/fruit-veg/ripe---ready';

$scraper = new Sainsburys\Scraper();
$results = $scraper->scrape($url);

echo json_encode($results);