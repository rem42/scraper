Scraper
=======

Scraper can handle multiple request type and transform them into object in order to create some API.

Installation
------------

````bash
$ composer require rem42/scraper "^1.0"
````

Configuration
-------------

Nothing Needed

Usage
-----

This library can't work in stand alone. It need another library to make request for exemple with ScraperGooglePrint

````php
<?php

    use Scraper\Scraper\Client;
    use Scraper\ScraperGooglePrint\Request\GooglePrintSearchRequest;
    
    $request = new GooglePrintSearchRequest();
    $request->setAccessToken("Your google access token");
    
    $client = new Client();
    $result = $client->api($request);
````

In the result var there is the GooglePrintSearch entity wich contains all the result from in google json response serialized in this entity.


List of supported Scraper
-------------------------

- [Allocine](https://github.com/rem42/scraper-allocine)
- [Avis Vérifiés](https://github.com/rem42/scraper-avis-verifies)
- [Colissimo](https://github.com/rem42/scraper-colissimo)
- [Google Play](https://github.com/rem42/scraper-google-play)
- [Google Print](https://github.com/rem42/scraper-google-print)
- [Itunes](https://github.com/rem42/scraper-itunes)
- [ShortPixel](https://github.com/rem42/scraper-shortpixel)
- [TinyPNG](https://github.com/rem42/scraper-tinypng)
- [Trustpilot](https://github.com/rem42/scraper-trustpilot)
