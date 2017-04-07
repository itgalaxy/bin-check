# bin-check

[![Latest Stable Version](https://poser.pugx.org/itgalaxy/bin-check/v/stable)](https://packagist.org/packages/itgalaxy/bin-check)
[![Travis Build Status](https://img.shields.io/travis/itgalaxy/bin-check/master.svg?label=build)](https://travis-ci.org/itgalaxy/bin-check)
[![Build status](https://ci.appveyor.com/api/projects/status/0bnqix0n59j1byrc?svg=true)](https://ci.appveyor.com/project/evilebottnawi/bin-check)
[![Coverage Status](https://coveralls.io/repos/github/itgalaxy/bin-check/badge.svg?branch=master)](https://coveralls.io/github/itgalaxy/bin-check?branch=master)

Check if a binary is working by checking its exit code

## Install

The utility can be installed with Composer:

```shell
$ composer require bin-check
```

## Usage

```php
use Itgalaxy\BinCheck\BinCheck;

try {
    $binCheck = new BinCheck('path/to/bin', ['--help']);
    $binCheck->check();
} catch (\Exception $exception) {
    echo $exception->message;
}
```

## Related

- [bin-check](https://github.com/itgalaxy/bin-check) - Thanks you for inspiration.

## Contribution

Feel free to push your code if you agree with publishing under the MIT license.

## [Changelog](CHANGELOG.md)

## [License](LICENSE)
