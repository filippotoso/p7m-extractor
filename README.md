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
P7M::extract('test.pdf.p7m', 'test.pdf', 'C:/Program Files/OpenSSL-Win64/bin/openssl.exe')
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

or as the last parameter to the `extract` static method:

```php
$success = P7M::convert('source.pdf.p7m', 'destination.pdf', '/custom/path/to/openssl');
```

If you want to get the content as a string instead of saving it to a file you can use the get() method or the extract() static method.


By default the package will assume that the `openssl` command is `smime`.
If necessary you can use `cms` instead

```php
use FilippoToso\P7MExtractor\P7M;

$success = (new P7M('/custom/path/to/openssl'))
    ->setCommand('cms')
    ->setSource('source.pdf.p7m')
    ->setDestination('destination.pdf')
    ->save();
```

You can also add another param to the command line, for example:

```php
use FilippoToso\P7MExtractor\P7M;

$success = (new P7M('/custom/path/to/openssl'))
    ->setCommand('cms')
    ->setExtraParam('-no_attr_verify')
    ->setSource('source.pdf.p7m')
    ->setDestination('destination.pdf')
    ->save();
```

The last example is usefull to solve issues on some kind of p7m files (probably old ones):

`cms` solve problemes like `Error reading S/MIME message - Exit Code: 2 (Misuse of shell builtins)`
`-no_attr_verify` is usefull when receiving errors like this: `rsa routines:int_rsa_verify:bad signature - CMS routines:CMS_SignerInfo_verify:verification failure`

(no_attr_verify: Do not verify the signer's attribute of a signature)
