[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abacaphiliac/php-extractor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/abacaphiliac/php-extractor/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/abacaphiliac/php-extractor/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/abacaphiliac/php-extractor/?branch=master)
[![Build Status](https://travis-ci.org/abacaphiliac/php-extractor.svg?branch=master)](https://travis-ci.org/abacaphiliac/php-extractor)

# abacaphiliac/php-extractor
Provides a simple abstraction to extract values, and sets of values, into an array.

# Installation
```bash
composer require abacaphiliac/php-extractor
```

# Usage
```php
$extractor = new \Abacaphiliac\Extractor\ExtractorChain([
    new YourCustomSessionExtractor($session),
    new SomeOtherDataProvider($dependency),
]);

$mergedData = $extractor->extract();
```

# Dependencies
See [composer.json](composer.json).

## Contributing
```bash
git clone git@github.com:abacaphiliac/php-extractor.git && cd php-extractor
composer update && vendor/bin/phing
```

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
