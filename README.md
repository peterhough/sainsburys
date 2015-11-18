About
=====
Peter Hough's response to the Sainsbury's Software Engineering Test

Installation
============
`git clone https://github.com/peterhough/sainsburys.git`
`composer install`

Usage
=====
The default action is to scrape the 'Ripe & ready' fruits page: http://www.sainsburys.co.uk/shop/gb/groceries/fruit-veg/ripe---ready
`scrape`

Specify a URL to scrape other pages:
`scrape http://www.sainsburys.co.uk/shop/gb/groceries/drinks/ale-stout`

Running Tests
=============
`vendor/bin/codecept run`

Dependencies
============
* https://github.com/FriendsOfPHP/Goutte
* https://github.com/Codeception/Codeception