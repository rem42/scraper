# Scraper

![Packagist version](https://badgen.net/packagist/v/rem42/scraper)
![Packagist download](https://badgen.net/packagist/dt/rem42/scraper)
![Packagist name](https://badgen.net/packagist/name/rem42/scraper)
![Packagist php version](https://badgen.net/packagist/php/rem42/scraper)
![Github licence](https://badgen.net/github/license/rem42/scraper)
![Depenabot](https://badgen.net/dependabot/rem42/scraper?icon=dependabot)
![Codeclimate lines of code](https://badgen.net/codeclimate/loc/rem42/scraper)
![Codeclimate maintainability](https://badgen.net/codeclimate/maintainability/rem42/scraper)

Scraper is a PHP library designed to simplify making HTTP requests and processing responses, particularly for interacting with APIs or scraping web content. It provides a structured way to define different types of requests and map responses to PHP objects.

## Installation

```bash
composer require rem42/scraper "^2.0"
```

## Requirements

- PHP: ^7.4 || ^8.0

## Usage

The core of the library is the `Scraper\Scraper\Client` class, which sends `Scraper\Scraper\Request\ScraperRequest` objects.

### Basic Example

Here's a conceptual example of how to make a request:

```php
<?php

require 'vendor/autoload.php';

use Scraper\Scraper\Client;
use Scraper\Scraper\Request\ScraperRequest; // Base request class
use Scraper\Scraper\Annotation\Method; // For annotation-based request definition
use Scraper\Scraper\Annotation\Path;   // For annotation-based request definition
use Symfony\Component\HttpClient\HttpClient; // Example PSR-18 HTTP client

// 1. Create an HTTP client instance (PSR-18 compatible)
// You can use any client that implements Psr\Http\Client\ClientInterface
$httpClient = HttpClient::create();

// 2. Create the Scraper Client instance
$client = new Client($httpClient);

// 3. Define your custom Request class
// Requests are typically defined by extending ScraperRequest and using annotations
// or by implementing specific interfaces for request features.

/**
 * @Method("GET")
 * @Path("/users/{userId}")
 */
class GetUserRequest extends ScraperRequest
{
    /**
     * @var string
     */
    public string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}

// 4. Create an instance of your request
$request = new GetUserRequest('123');

// 5. Send the request
try {
    // The response type depends on how the corresponding Api class (e.g., GetUserApi)
    // processes the response. It could be an object, an array, or a string.
    $response = $client->send($request);

    // Process your response
    var_dump($response);

} catch (\Scraper\Scraper\Exception\ScraperException $e) {
    // Handle exceptions specific to the Scraper library
    echo "Scraper Error: " . $e->getMessage() . "\n";
} catch (\Symfony\Contracts\HttpClient\Exception\ExceptionInterface $e) {
    // Handle exceptions from the underlying HTTP client
    echo "HTTP Client Error: " . $e->getMessage() . "\n";
}

?>
```

**Note:** For the above example to be fully functional, you would also need to define a corresponding `Api` class (e.g., `GetUserApi extends AbstractApi`) that handles the transformation of the HTTP response into the desired PHP object or data structure. The library uses reflection to find an `Api` class that matches the `Request` class name (e.g., `MyRequest` -> `MyApi`).

## Request Features

The library allows you to define various aspects of your HTTP request by having your `ScraperRequest` subclass implement specific interfaces:

*   **`Scraper\Scraper\Request\RequestAuthBasic`**: Implement this to add Basic HTTP Authentication.
    *   Your request class will need a `getLogin()` and `getPassword()` method.
    *   The `Client` will use these to set the `auth_basic` option.
*   **`Scraper\Scraper\Request\RequestAuthBearer`**: Implement this for Bearer Token Authentication.
    *   Your request class will need a `getBearer()` method.
    *   The `Client` will use this to set the `auth_bearer` option.
*   **`Scraper\Scraper\Request\RequestBody`**: For sending a raw request body (e.g., form data as a string or resource).
    *   Your request class will need a `getBody()` method.
    *   The `Client` will use this to set the `body` option.
*   **`Scraper\Scraper\Request\RequestBodyJson`**: For sending a JSON request body.
    *   Your request class will need a `getJson()` method (returning an array).
    *   The `Client` will use this to set the `json` option.
*   **`Scraper\Scraper\Request\RequestHeaders`**: For adding custom request headers.
    *   Your request class will need a `getHeaders()` method (returning an associative array of headers).
    *   The `Client` will use this to set the `headers` option.
*   **`Scraper\Scraper\Request\RequestQuery`**: For adding URL query parameters.
    *   Your request class will need a `getQuery()` method (returning an associative array of query parameters).
    *   The `Client` will use this to set the `query` option.
*   **`Scraper\Scraper\Request\RequestException`**: Implement this to control whether an exception should be thrown on HTTP errors (4xx-5xx).
    *   Your request class will need an `isThrow()` method returning a boolean.

These interfaces are now classes that your custom request class should extend if you want to use their functionality. For example, `class MyRequest extends RequestAuthBasic { ... }`.
The `Client`'s `buildOptions` method checks if your request object is an instance of these classes and calls the relevant `getOptions()` method defined in the `App\Request\RequestOptionProvider` interface (which these classes implement) to construct the final HTTP client options.

## Contributing

Contributions are welcome! Please feel free to submit pull requests or open issues.
(TODO: Add more specific contribution guidelines if necessary)

## License

This library is licensed under the MIT License. (Assuming MIT based on common practice and `badgen.net/github/license/rem42/scraper` badge, but this should be confirmed with a `LICENSE` file).
```
