Scraper
=======

Boîte à outils légère pour construire des "scrapers" réutilisables :
- Déclarez une classe Request annotée par l'attribut PHP `#[Scraper(...)]`.
- Fournissez la classe Api correspondante (remplacez "Request" par "Api" dans le nom) qui étend `Scraper\Api\AbstractApi` et implémente `execute()`.
- Utilisez `Scraper\Client` avec un `HttpClientInterface` pour exécuter la requête et récupérer l'objet désérialisé.

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

Courte introduction
-------------------

Le package centralise la logique suivante :
1. Une Request (sous `src/Request/`) définit les données nécessaires et expose des getters utilisés dans les variables de chemin.
2. L'attribut `#[\Scraper\Scraper\Attribute\Scraper(...)]` (sur la Request) décrit `method`, `scheme`, `host`, `path`.
3. `Scraper\Client::send()` lit cet attribut (via `ExtractAttribute`), construit les options HTTP (headers, query, body, json, auth) selon les interfaces implémentées par la Request, puis effectue l'appel HTTP.
4. La classe Api correspondante (ex : `FooApi`) est instanciée et sa méthode `execute()` retourne l'objet/array/string final.

Quickstart (exemple minimal)
----------------------------

Exemple schématique (adapter selon votre autoload / imports) :

```php
use Symfony\Component\HttpClient\HttpClient;
use Scraper\Scraper\Client;

#[\Scraper\Scraper\Attribute\Scraper(
	method: \Scraper\Scraper\Attribute\Method::GET,
	scheme: \Scraper\Scraper\Attribute\Scheme::HTTPS,
	host: 'example.com',
	path: '/items/{id}'
)]
class ItemRequest extends \Scraper\Scraper\Request\ScraperRequest
{
	public function __construct(private string $id) {}
	public function getId(): string { return $this->id; }
}

// Provide a matching Api: ItemApi extends Scraper\Scraper\Api\AbstractApi

$http = HttpClient::create();
$client = new Client($http);
$result = $client->send(new ItemRequest('42'));
```

Conventions importantes
----------------------

- PSR-4 namespace racine : `Scraper\\Scraper\\` -> `src/` (voir `composer.json`).
- Naming convention : `XRequest` -> `XApi` (Client effectue ce remplacement automatiquement via réflexion).
- Dans l'attribut `path`, les variables `{name}` sont remplacées par l'appel `getName()` sur l'instance Request (voir `src/Attribute/ExtractAttribute.php`).
- Implémentez les interfaces dans `src/Request/` pour activer les options : `RequestHeaders`, `RequestQuery`, `RequestBody`, `RequestBodyJson`, `RequestAuthBearer`, `RequestAuthBasic`.

Tests / qualité / style
-----------------------

- Lancer les tests unitaires :

```bash
composer run unit-test
# ou
./vendor/bin/phpunit
```

- Analyse statique (phpstan) :

```bash
composer run static-analysis
```

- Vérifier / appliquer le style (php-cs-fixer) :

```bash
composer run code-style-check
composer run code-style-fix
```

Compatibilité PHP
-----------------

`composer.json` demande `php: ^8.4` — le code utilise des enums et des types récents, il est donc recommandé d'utiliser PHP 8.4+.

Ressources et documentation pour agents
--------------------------------------

- Fichier d'aide pour agents : `AGENTS.md` (conseils, patterns, commandes). Voir `packages/scraper/AGENTS.md`.
- Points clés du code : `src/Client.php`, `src/Attribute/ExtractAttribute.php`, `src/Factory/SerializerFactory.php`.

Liste (non exhaustive) de scrapers publiés
-----------------------------------------
- rem42/scraper-allocine
- rem42/scraper-colissimo
- rem42/scraper-deezer
- rem42/scraper-giantbomb
- rem42/scraper-jeuxvideo
- rem42/scraper-prestashop
- rem42/scraper-shopify
- rem42/scraper-tmdb
- rem42/scraper-tnt

Contribuer
---------

Voir `AGENTS.md` pour les règles et patterns à respecter. Pour les PRs : tests verts + phpstan level max.

