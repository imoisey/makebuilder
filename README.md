# Imoisey\Makebuilder

Makebuilder is an easy library for building Makefile.

## Installation

```bash
composer require imoisey/makebuilder
```

## Usage

```php
use Imoisey\Makebuilder\MakeBuilder;

$makeBuilder = new MakeBuilder();
$makeBuilder->createTarget('install', function ($target) {
   $target->addCommand('@echo --- Install ---');
});

$makeBuilder->build('Makefile');
```                      

Makefile:

```text
install:
    @echo --- Install ---
```


## License
[MIT](https://choosealicense.com/licenses/mit/)