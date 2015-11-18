<?php

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Codeception\Util\Fixtures;

/**
 * Load in Fixture files
 */
$fixtureDir = __DIR__ . '/fixtures/';

// http://www.sainsburys.co.uk/shop/gb/groceries/fruit-veg/ripe---ready
Fixtures::add(
    'ripeAndReady',
    new GuzzleResponse(
        200,
        ['Content-Type' => 'text/html; charset=UTF-8'],
        file_get_contents($fixtureDir . 'ripe---ready.html')
    )
);

// http://www.sainsburys.co.uk/shop/gb/groceries/ripe---ready/sainsburys-avocado-xl-pinkerton-loose-300g
Fixtures::add(
    'avocado',
    new GuzzleResponse(
        200,
        ['Content-Type' => 'text/html; charset=UTF-8'],
        file_get_contents($fixtureDir . 'sainsburys-avocado-xl-pinkerton-loose-300g.html')
    )
);

// http://www.sainsburys.co.uk/shop/gb/groceries/drinks/ale-stout
Fixtures::add(
    'aleAndStout',
    new GuzzleResponse(
        200,
        ['Content-Type' => 'text/html; charset=UTF-8'],
        file_get_contents($fixtureDir . 'ale-stout.html')
    )
);

// http://www.sainsburys.co.uk/shop/gb/groceries/ale-stout/adnams-broadside-ale-500ml
Fixtures::add(
    'broadside',
    new GuzzleResponse(
        200,
        ['Content-Type' => 'text/html; charset=UTF-8'],
        file_get_contents($fixtureDir . 'adnams-broadside-ale-500ml.html')
    )
);