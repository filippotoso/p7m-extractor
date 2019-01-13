<?php

use FilippoToso\P7MExtractor\P7M;

require_once(__DIR__ . '/../vendor/autoload.php');

// var_dump(P7M::extract('test.pdf.p7m', 'test.pdf', 'C:/Program Files/OpenSSL-Win64/bin/openssl.exe'));

var_dump(P7M::extract('test.pdf.p7m', 'C:/Program Files/OpenSSL-Win64/bin/openssl.exe'));
