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
    protected $command;
    protected $extraParam;

    public function __construct(string $binPath = null)
    {
        $this->binPath = $binPath ?? '/usr/bin/openssl';
    }

    public function setSource(string $source) : self
    {
        $this->checkSource($source);

        $this->source = $source;

        return $this;
    }

    protected function checkSource(string $source)
    {
        if (!is_readable($source)) {
            throw new P7MNotFound(sprintf('Could not find or read p7m `%s`', $source));
        }
    }


    public function setDestination(string $destination) : self
    {
        $this->checkDestination($destination);

        $this->destination = $destination;

        return $this;
    }

    protected function checkDestination(string $destination)
    {
        if (file_exists($destination) && !is_writable($destination)) {
            throw new FileNotWritable(sprintf('Could not wrtie file `%s`', $destination));
        }
    }

    public function setCommand(string $command) : self
    {
        $this->command = $command;

        return $this;
    }

    public function setExtraParam(string $extraParam) : self
    {
        $this->extraParam = $extraParam;

        return $this;
    }

    public function save()
    {

        $this->checkSource($this->source);
        $this->checkDestination($this->destination);

        $process = $this->getProcess();
        $process->run();
        if (!$process->isSuccessful()) {
            throw new CouldNotExtractFile($process);
        }

        return true;

    }

    protected function getProcess()
    {
        $command = 'smime';
        if ($this->command) {
            $command = $this->command;
        }

        $options = [$this->binPath, $command, '-verify', '-noverify', '-binary', '-in', $this->source, '-inform', 'DER', '-out', $this->destination];

        if ($this->extraParam) {
            $options[] = $this->extraParam;
        }

        return new Process($options);
    }

    public function get() {

        $this->checkSource($this->source);

        $originalDestination = $this->destination;
        $this->destination = $this->getTemporaryFile();

        $process = $this->getProcess();
        $process->run();
        if (!$process->isSuccessful()) {
            throw new CouldNotExtractFile($process);
        }

        $content = file_get_contents($this->destination);

        $this->destination = $originalDestination;

        return $content;

    }

    protected function getTemporaryFile() {
        $tempDir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
        return tempnam($tempDir, 'p7m');
    }

    public static function convert(string $source, string $destination, string $binPath = null)
    {
        return (new static($binPath))
            ->setSource($source)
            ->setDestination($destination)
            ->save()
        ;
    }

    public static function extract(string $source, string $binPath = null)
    {
        return (new static($binPath))
            ->setSource($source)
            ->get()
        ;
    }

}
