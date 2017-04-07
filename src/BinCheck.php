<?php
namespace Itgalaxy\BinCheck;

use Itgalaxy\BinCheck\Exception\FileNotExecutableException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ProcessBuilder;
use Webmozart\PathUtil\Path;

class BinCheck
{
    protected $bin = null;

    protected $args = null;

    public function __construct($bin, $args = ['--help'])
    {
        if (!is_string($bin)) {
            throw new \InvalidArgumentException('Option `bin` should be string');
        }

        if (!is_array($args)) {
            throw new \InvalidArgumentException('Option `args` should be array');
        }

        $this->bin = Path::canonicalize($bin);
        $this->args = $args;
    }

    public function check()
    {
        $bin = $this->bin;
        $isExecutable = is_executable($bin);

        if (!$isExecutable) {
            throw new FileNotExecutableException(
                'Path `' . $bin . '` is not executable. Make sure it has the right permissions.'
            );
        }

        $builder = new ProcessBuilder();
        $builder->setPrefix($bin);
        $process = $builder
            ->setArguments($this->args)
            ->getProcess();

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process;
    }
}
