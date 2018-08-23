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

- In progress
