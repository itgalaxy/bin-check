<?php
namespace Itgalaxy\BinCheck\Tests;

use Itgalaxy\BinCheck\BinCheck;
use Itgalaxy\BinCheck\Exception\FileNotExecutableException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Comparator\ComparisonFailure;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Webmozart\PathUtil\Path;

class BinCheckTest extends TestCase
{
    protected $bin = [
        'darwin' => __DIR__ . '/fixtures/optipng-osx',
        'linux' => __DIR__ . '/fixtures/optipng-linux',
        'winnt' => __DIR__ . '/fixtures/optipng-winnt.exe',
        'invalid-permissions' => __DIR__ . '/fixtures/invalid-permissions',
        'corrupted' => [
            'darwin' => __DIR__ . '/fixtures/corrupted',
            'linux' => __DIR__ . '/fixtures/corrupted',
            'winnt' => __DIR__ . '/fixtures/corrupted.exe'
        ]
    ];

    public function testThrowErrorOnInvalidBinArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `bin` should be string');

        new BinCheck([]);
    }

    public function testThrowErrorOnInvalidArgsArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `args` should be array');

        $platform = strtolower(PHP_OS);

        new BinCheck($this->bin[$platform], 'foo');
    }

    public function testWorksCorrectly()
    {
        $exception = null;
        $platform = strtolower(PHP_OS);

        try {
            $binCheck = new BinCheck($this->bin[$platform]);
            $binCheck->check();
        } catch (ComparisonFailure $exception) {}

        $this->assertNull($exception, 'Unexpected ComparisonFailure');
    }

    public function testErrorIfRightsPermissionsAreNotSetCorrectly()
    {
        $bin = Path::canonicalize($this->bin['invalid-permissions']);

        chmod($bin, 0444);

        $this->expectException(FileNotExecutableException::class);

        $binCheck = new BinCheck($bin);
        $binCheck->check();
    }

    public function testErrorIfBinaryDontWorkCorrectly()
    {
        $platform = strtolower(PHP_OS);
        $bin = Path::canonicalize($this->bin['corrupted'][$platform]);

        $this->expectException(ProcessFailedException::class);

        $binCheck = new BinCheck($bin);
        $binCheck->check();
    }
}
