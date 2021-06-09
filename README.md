# Loyalty Card
A Magento extension to associate a loyalty card number to a customer.

Used as a sample to show how to apply the principles of hexagonal architecture. 

## Installation
In your `composer.json` add the following:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:aleron75/ddd_loyalty_card.git"
    }
  ],
  "require": {
    "ddd/loyalty-card": "dev-master"
  },
  "autoload-dev": {
    "psr-4": {
      "Ddd\\Loyalty\\": "vendor/ddd/loyalty-card/test"
    }
  }
}
```

Then, from the Magento root directory, run:

    composer update

Then, from the Magento root directory, run:

    bin/magento setup:upgrade

## Extension structure
This extension has been developed according to the [Advanced Web Application Architecture](https://matthiasnoback.nl/book/advanced-web-application-architecture/) principles.

Here is a picture that explains how different components are layered:

![How components are layered](https://raw.githubusercontent.com/aleron75/aleron75.github.io/master/img/ddd-journey/awaa-big-picture.png)

The structure of the extension is that of a typical Magento extension, except for the `Application` and `Domain` folders under `src` and `Adapter` and `UseCase` folders under `test`.

Here is the main folder structure:

    ddd/
    └─ loyalty/
    ├─ src/
    │  ├─ Application/
    │  ├─ Controller/
    │  ├─ Domain/
    │  ├─ etc/
    │  ├─ Model/
    │  ├─ view/
    │  └─ ViewModel/
    └─ test/
       ├─ Adapter/
       ├─ Unit/
       └─ UseCase/

`Application`, `Domain`, `Unit` and `UseCase` folders belong to the **Core** layer, which is developed in pure **framework-agnostic** PHP.

The other folders and files belong to the **Infrastructure** layer.

The `Adapter` folder contains what in Magento are called *Integration tests*. They are written accordingly to the hexagonal architecture principles, so they are simpler compared to typical Magento integration tests.

In fact, integration tests are split into **incoming adapter tests** and **outgoing adapter tests**.

Please, refer to [the slides accompanying the project](https://www.slideshare.net/aleron75/meet-magento-it-2021-principles-advantages-of-hexagonal-architecture-on-magento-249255352) for further details about how the extension is structured.

You can also read more on [the series of insights on DDD](http://aleron75.com/blog/tag/ddd/) published on my blog.

## Run the unit tests
From the Magento root directory, run any of:

    vendor/bin/phpunit --colors --bootstrap=vendor/autoload.php vendor/ddd/loyalty-card/test/Unit/
    vendor/bin/phpunit --testdox --colors --bootstrap=vendor/autoload.php vendor/ddd/loyalty-card/test/Unit/

## Run the use-case tests
From the Magento root directory, run any of:

    vendor/bin/phpunit --colors --bootstrap=vendor/autoload.php vendor/ddd/loyalty-card/test/UseCase/
    vendor/bin/phpunit --testdox --colors --bootstrap=vendor/autoload.php vendor/ddd/loyalty-card/test/UseCase/

## Run the adapter tests
Note: prepare your integration test environment following the [instructions here](https://devdocs.magento.com/guides/v2.4/test/integration/integration_test_execution.html).

Create a `magento_integration_tests` additional database.

Copy the following files:
- `Loyalty/test/Adapter/phpunit.xml.dist` into `dev/tests/integation/phpunit.xml`
- `Loyalty/test/Adapter/config-global.php.dist` into `dev/tests/integation/etc/config-global.php`
- `Loyalty/test/Adapter/install-config-mysql.php.dist` into `dev/tests/integation/etc/install-config-mysql.php`

On Magento 2.4.x, don't forget to add elasticsearch configuration in the `dev/tests/integration/etc/install-config-mysql.php` file; if you use Warden, here are the additional array keys to use:

    'elasticsearch-host' => 'elasticsearch',
    'elasticsearch-port' => '9200',
    'elasticsearch-index-prefix' => 'magento2',
    'elasticsearch-enable-auth' => '0',
    'elasticsearch-timeout' => '15',

They are already inserted in the distributed file.

From the Magento root directory, run any of:

    cd dev/tests/integration/ && ../../../vendor/bin/phpunit --testsuite "Ddd_Loyalty Adapter Tests" && cd ../../../
    cd dev/tests/integration/ && ../../../vendor/bin/phpunit --testdox --testsuite "Ddd_Loyalty Adapter Tests" && cd ../../../

## License
Refer to the [LICENSE](LICENSE) file.

## Acknowledgements
I want to thank the following people that inspired this work and supported me directly or indirectly:

- [Sara Del Grosso](https://twitter.com/saradg82), a colleague of mine, for helping me outlining and debugging the ideas and the code behind this project.
- [Ivan Chepurnyi](https://twitter.com/IvanChepurnyi), for his presentation about [the challenges of architecting Magento 2 customizations](https://www.youtube.com/watch?v=kd40IkIRzuk)  
- [James Cowie](https://twitter.com/jcowie), for his presentation about [the future of Testing Magento 2 code](https://www.youtube.com/watch?v=mHFEYGDUQ-k) 
- [Fabian Schmengler](https://twitter.com/fschmengler), for his contribution on [making it easier to write tests on Magento](https://github.com/tddwizard).
- [Matthias Noback](https://twitter.com/matthiasnoback), for writing [the book that changed my way of designing software](https://matthiasnoback.nl/book/advanced-web-application-architecture/), definitively pushing me to start working on this project and presentation.
