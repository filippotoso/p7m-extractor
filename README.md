# P7M Extractor

A simple class that allows to extract the original file from a signed p7m file.

## Requirements

- PHP 7.0+
- Symphony Prcess 3.3 or 4.0+

## Requirements

Behind the scenes this package leverages [openssl](https://it.wikipedia.org/wiki/OpenSSL). You can verify if the binary installed on your system by issueing this command:

```bash
which openssl
```

If it is installed it will return the path to the binary.

## Installation

You can install the package via composer:

```bash
composer require filippo-toso/p7m-extractor
```

## Usage

Extracting text from a pdf is easy.

```php
use FilippoToso\P7MExtractor\P7M;

$success = (new P7M())
    ->setSource('source.pdf.p7m')
    ->setDestination('destination.pdf')
    ->save();
```

Or easier:

```php
use FilippoToso\P7MExtractor\P7M;

$success = P7M::convert('source.pdf.p7m', 'destination.pdf');
```

By default the package will assume that the `openssl` command is located at `/usr/bin/openssl`.
If it is located elsewhere pass its binary path to constructor

```php
use FilippoToso\P7MExtractor\P7M;

$success = (new P7M('/custom/path/to/openssl'))
    ->setSource('source.pdf.p7m')
    ->setDestination('destination.pdf')
    ->save();
```

or as the last parameter to the `extract()` static method:

```php
$success = P7M::convert('source.pdf.p7m', 'destination.pdf', '/custom/path/to/openssl');
```

If you want to get the content as a string instead of saving it to a file you can use the `get()` method or the `extract()` static method.

If you need to change the command or add additional parameters to openssl, you can use the `setParams()` methods

```php
use FilippoToso\P7MExtractor\P7M;

$success = (new P7M('/custom/path/to/openssl'))
    ->setParams(['{$openssl}', 'cms', '-verify', '-noverify', '-no_attr_verify', '-binary', '-in', '{$source}', '-inform', 'DER', '-out', '{$destination}'])
    ->setSource('source.pdf.p7m')
    ->setDestination('destination.pdf')
    ->save();
```

The {$openssl}, {$source}, {$destination} tokens are replace dinamically with the openssl executable, source and destination paths.