[![Minimum PHP version: 8.2.0](https://img.shields.io/badge/php-8.2.0%2B-blue.svg)](https://packagist.org/packages/stas-plov/dto-validator-bundle)
[![Latest Stable Version](http://poser.pugx.org/stas-plov/dto-validator-bundle/v)](https://packagist.org/packages/stas-plov/dto-validator-bundle) 
[![Total Downloads](http://poser.pugx.org/stas-plov/dto-validator-bundle/downloads)](https://packagist.org/packages/stas-plov/dto-validator-bundle) 
[![Latest Unstable Version](http://poser.pugx.org/stas-plov/dto-validator-bundle/v/unstable)](https://packagist.org/packages/stas-plov/dto-validator-bundle) 
[![License](http://poser.pugx.org/stas-plov/dto-validator-bundle/license)](https://packagist.org/packages/stas-plov/dto-validator-bundle) 

# StasPlovDtoValidatorBundle

## About 

The StasPlovDtoValidatorBundle Validating the Request by DTO in the context of symfony.

The core idea of StasPlovDtoValidatorBundle is to validate input data in the controller from the Request
using so-called DTO (Data Transfer Object) entities.


```php
#[ValidateDto(data: 'createDto', class: CreateDto::class)]
#[Route(path: '/create/user', name: 'api-user-create', methods: ['POST'])]
public function createUser(CreateDto $createDto): Response {
	// ... some code
}
```

The `$createDto` variable will contain all the data described in the corresponding `CreateDto` class.

## Installation

Require the `stas-plov/dto-validator-bundle` package in your composer.json and update your dependencies:

```bash
composer require stas-plov/dto-validator-bundle
```

Flex, you'll need to enable it manually as explained [in the docs][1].

## Usage

See [the documentation][2] for usage instructions.

## License

Released under the MIT License, see LICENSE.

[1]: https://symfony.com/doc/current/setup/flex.html
[2]: ./Resources//doc//HowToUse.md
