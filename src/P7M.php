<?php

namespace FilippoToso\P7MExtractor;

use FilippoToso\P7MExtractor\Exceptions\FileNotWritable;
use FilippoToso\P7MExtractor\Exceptions\CouldNotExtractFile;
use FilippoToso\P7MExtractor\Exceptions\P7MNotFound;
use Symfony\Component\Process\Process;

class P7M
{
    protected $source;
    protected $destination;
    protected $binPath;

    public function __construct(string $binPath = null)
    {
        $this->binPath = $binPath ?? '/usr/bin/openssl';
    }

    public function setSource(string $source) : self
    {
        if (!is_readable($source)) {
            throw new P7MNotFound(sprintf('Could not find or read p7m `%s`', $source));
        }

        $this->source = $source;

        return $this;
    }

    public function setDestination(string $destination) : self
    {
        if (file_exists($destination) && !is_writable($destination)) {
            throw new FileNotWritable(sprintf('Could not wrtie file `%s`', $destination));
        }

        $this->destination = $destination;

        return $this;
    }

    public function save()
    {
        $options = [$this->binPath, 'smime', '-verify', '-noverify', '-binary', '-in', $this->source, '-inform', 'DER', '-out', $this->destination];
        $process = new Process($options);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new CouldNotExtractFile($process);
        }

        return true;
    }

    public static function extract(string $source, string $destination, string $binPath = null)
    {
        return (new static($binPath))
            ->setSource($source)
            ->setDestination($destination)
            ->save()
        ;
    }
}
