AGENTS.md
=========

But et vue d'ensemble
---------------------
Ce package fournit un petit framework de "scraper" réutilisable :
- Définition d'une requête (classe Request) annotée par l'attribut `#[Scraper(...)]`.
- Un `Client` qui transforme la Request en appel HTTP (via un `HttpClientInterface` injecté), puis instancie la classe `Api` correspondante pour parser la réponse.

Points d'entrée importants
-------------------------
- `src/Client.php` — méthode principale : `Client::send(ScraperRequest $request)`.
  - construit les options HTTP à partir des interfaces implémentées par la Request (headers, query, body, json, auth). Voir `buildOptions()`.
  - récupère l'attribut `Scraper` via `ExtractAttribute::extract()` et appelle le endpoint.
  - instancie la classe Api correspondante en remplaçant "Request" par "Api" dans le nom de classe (voir `getApiReflectionClass()`).

Patterns et conventions spécifiques
---------------------------------
- PSR-4 autoload: namespace racine `Scraper\Scraper\` -> `src/` (voir `composer.json` `autoload`).
- Naming convention Request->Api : si vous créez `FooRequest`, fournissez `FooApi` (extends `Api\AbstractApi`) — le `Client` s'en sert automatiquement.
- Attributs PHP: les Requests sont annotées par `#[Scraper(...)]` (voir `src/Attribute/Scraper.php`) : contient method, scheme, host, path.
- Variable substitution dans `path`: `{name}` est remplacé par l'appel `getName()` sur l'objet Request (voir `src/Attribute/ExtractAttribute.php`).
- Options HTTP: implémentez les interfaces de `src/Request/` pour activer les options automatiques :
  - `RequestHeaders` -> `getHeaders()` → option `headers`
  - `RequestQuery` -> `getQuery()` → option `query`
  - `RequestBody` / `RequestBodyJson` -> `getBody()` / `getJson()` → option `body` / `json`
  - `RequestAuthBearer` / `RequestAuthBasic` -> `getBearer()` / `getAuthBasic()` → `auth_bearer` / `auth_basic`

Composants clés (exemples)
-------------------------
- `src/Client.php` — orchestrateur principal, error handling et mapping Request->Api.
- `src/Attribute/ExtractAttribute.php` — lit les attributs `#[Scraper]`, fusionne héritages et remplace les variables dans `path`.
- `src/Factory/SerializerFactory.php` — création du `Serializer` Symfony utilisé par les API pour dénormaliser les réponses.
- `src/Request/ScraperRequest.php` — classe de base pour les Requests (ssl, auth basic helpers).

Dépendances et intégrations
---------------------------
- Dépendances runtime (extraites de `composer.json`):
  - `symfony/http-client-contracts` (injection `HttpClientInterface`)
  - `symfony/serializer-pack` (Serializer + normalizers)
- Extensions suggérées: `ext-json`, `ext-simplexml`, `ext-soap` (voir `suggest` dans `composer.json`).

Flux d'exécution succinct
------------------------
1. L'utilisateur instancie une Request (ex: `new FooRequest()`), éventuellement configure headers/query/body.
2. `Client::send($request)` → `ExtractAttribute::extract($request)` pour construire URL/méthode.
3. `HttpClientInterface->request(...)` est appelé avec les options produites par `buildOptions()`.
4. Une classe `FooApi` (remplacement Request->Api) est instanciée et sa méthode `execute()` est retournée.

Workflows dev (build / test / QA)
--------------------------------
- Tests unitaires: depuis la racine du package ou du monorepo, lancer:

```bash
composer run unit-test
# ou
./vendor/bin/phpunit -c packages/scraper/phpunit.xml
```

- Analyse statique: `composer run static-analysis` (phpstan) — configuration : `./vendor/bin/phpstan analyse src --level=max`.
- Format / style: `composer run code-style-check` et `composer run code-style-fix` (php-cs-fixer).

Conseils pratiques pour les agents/automates
-------------------------------------------
- Pour ajouter un nouveau scraper pour un site :
  1. Créer `src/Request/MySiteRequest.php` extends `ScraperRequest` et ajoutez les getters utilisés par `{...}` dans le path.
  2. Annoter la classe par `#[Scraper(method: Method::GET, scheme: Scheme::HTTPS, host: 'example.com', path: '/foo/{id}')]`.
  3. Créer `src/Api/MySiteApi.php` extends `Api\AbstractApi` et implémenter `execute(): object|array|bool|string` pour parser `ResponseInterface` via `$this->serializer`.

- Pour simuler appels HTTP en tests, fournissez un `HttpClientInterface` mock et vérifiez que `Client::send()` instancie la bonne Api (voir `tests/ClientTest.php`).
- Rechercher points fragiles: parsing HTML/XML — utilisez `symfony/serializer` et testez avec fixtures dans `tests/Fixtures/`.

Fichiers à connaître (références rapides)
----------------------------------------
- `composer.json` — scripts & autoload
- `src/Client.php` — orchestration
- `src/Attribute/ExtractAttribute.php` — logique d'attributs
- `src/Factory/SerializerFactory.php` — serializer
- `src/Request/` — interfaces pour options HTTP
- `src/Exception/` — `ScraperException`, `ScraperNotFoundException`, `ClassNotInitializedException`
- `tests/` — exemples d'usage et fixtures

Note sur la compatibilité PHP
----------------------------
`composer.json` indique `php: ^8.4` alors que le `README.md` mentionne `>= 8.1`. Pour les agents, préférer 8.4+ si possible (types et enums utilisés).

Ressources supplémentaires
-------------------------
- Lire `tests/Attribute/ExtractAttributeTest.php` pour exemples d'extraction d'attributs et de substitution de variables.

---
Fichier généré automatiquement pour aider les agents à contribuer rapidement.

