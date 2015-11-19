About
=====
Peter Hough's response to the Sainsbury's Software Engineering Test

Installation
============
```
git clone https://github.com/peterhough/sainsburys.git
composer install
```

Usage
=====
The default action is to scrape the 'Ripe & ready' fruits page: http://www.sainsburys.co.uk/shop/gb/groceries/fruit-veg/ripe---ready
```
scrape
```

Bonus Feature
-------------
Specify a URL to scrape other pages:
```
scrape http://www.sainsburys.co.uk/shop/gb/groceries/drinks/ale-stout
```

Running Tests
=============
```
vendor/bin/codecept run
```

Dependencies
============
* https://github.com/FriendsOfPHP/Goutte
* https://github.com/Codeception/Codeception

Known Issues
============
Sainsbury's website occasionally serves a JavaScript only encoded page redirect - probably a security feature.
Setting the "TS017d4e39" cookie seems to prevent this.

Due to this scraping an entire category of all pages of products is not feasible.
A possible solution may be to read the set-cookie response header for the "TS017d4e39" cookie