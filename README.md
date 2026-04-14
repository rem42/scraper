
Scraper
=======

Lightweight toolbox to build reusable "scrapers":
- Declare a Request class annotated with the PHP attribute `#[Scraper(...)]`.
- Provide the corresponding Api class (replace "Request" with "Api" in the name) which extends `\Scraper\Scraper\Api\AbstractApi` and implements `execute()`.
- Use `\Scraper\Scraper\Client` with an `HttpClientInterface` to execute the request and retrieve the deserialized object.

Badges
------

![Packagist version](https://flat.badgen.net/packagist/v/rem42/scraper)
![Packagist download](https://flat.badgen.net/packagist/dt/rem42/scraper)
![Packagist php version](https://flat.badgen.net/packagist/php/rem42/scraper)
![Github licence](https://flat.badgen.net/github/license/rem42/scraper)

Installation
------------

```bash
composer require rem42/scraper "^3.0"
```

Short introduction
------------------

The package centralizes the following logic:
1. A Request (under `src/Request/`) defines the necessary data and exposes getters used in path variables.
2. The attribute `#[\Scraper\Scraper\Attribute\Scraper(...)]` (on the Request) describes `method`, `scheme`, `host`, `path`.
3. `\Scraper\Scraper\Client::send()` reads this attribute (via `ExtractAttribute`), builds the HTTP options (headers, query, body, json, auth) according to the interfaces implemented by the Request, then performs the HTTP call.
4. The matching Api class (eg: `FooApi`) is instantiated and its `execute()` method returns the final object/array/string.

Quickstart (minimal example)
----------------------------


Schematic example (adapt according to your autoload/imports). Examples use `use` imports:

```php
use Symfony\Component\HttpClient\HttpClient;
use Scraper\Scraper\Client;
use Scraper\Scraper\Request\ScraperRequest;
use Scraper\Scraper\Attribute\Scraper;
use Scraper\Scraper\Attribute\Method;
use Scraper\Scraper\Attribute\Scheme;
use Scraper\Scraper\Api\AbstractApi;

#[Scraper(
	method: Method::GET,
	scheme: Scheme::HTTPS,
	host: 'example.com',
	path: '/items/{id}'
)]
class ItemRequest extends ScraperRequest
{
	public function __construct(private string $id) {}
	public function getId(): string { return $this->id; }
}

// Provide a matching Api: ItemApi extends AbstractApi

$http = HttpClient::create();
$client = new Client($http);
$result = $client->send(new ItemRequest('42'));
```

Important conventions
---------------------

- PSR-4 root namespace: `Scraper\\Scraper\\` -> `src/` (see `composer.json`).
- Naming convention: `XRequest` -> `XApi` (Client performs this replacement automatically using reflection).
- In the `path` attribute, variables `{name}` are replaced by calling `getName()` on the Request instance (see `src/Attribute/ExtractAttribute.php`).
- Implement the interfaces in `src/Request/` to enable options: `RequestHeaders`, `RequestQuery`, `RequestBody`, `RequestBodyJson`, `RequestAuthBearer`, `RequestAuthBasic`.

Tests / quality / style
-----------------------

- Run unit tests:

```bash
composer run unit-test
# or
./vendor/bin/phpunit
```

- Static analysis (phpstan):

```bash
composer run static-analysis
```

- Check / apply coding style (php-cs-fixer):

```bash
composer run code-style-check
composer run code-style-fix
```

PHP compatibility
-----------------

`composer.json` requires `php: ^8.4` — the code uses enums and recent types, so PHP 8.4+ is recommended.

Resources and documentation for agents
------------------------------------

- Agent helper file: `AGENTS.md` (tips, patterns, commands). See `packages/scraper/AGENTS.md`.
- Key code points: `src/Client.php`, `src/Attribute/ExtractAttribute.php`, `src/Factory/SerializerFactory.php`.

Non-exhaustive list of published scrapers
----------------------------------------
- rem42/scraper-allocine
- rem42/scraper-colissimo
- rem42/scraper-deezer
- rem42/scraper-giantbomb
- rem42/scraper-jeuxvideo
- rem42/scraper-prestashop
- rem42/scraper-shopify
- rem42/scraper-tmdb
- rem42/scraper-tnt

Contributing
------------

See `AGENTS.md` for rules and patterns to follow. For PRs: green tests + highest phpstan level.

