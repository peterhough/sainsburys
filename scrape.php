<?php
require __DIR__ . '/vendor/autoload.php';

$defaultUrl = 'http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true';

$url = isset($argv[1]) ? $argv[1] : $defaultUrl;

$scraper = new Sainsburys\Scraper();
$results = $scraper->scrape($url);

echo json_encode($results);