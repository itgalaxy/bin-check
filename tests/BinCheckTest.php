<?php
namespace BinCheck\Tests;

use BinCheck\BinCheck;
use PHPUnit\Framework\TestCase;
use Webmozart\PathUtil\Path;

class BinCheckTest extends TestCase
{
    private $bin = [
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

    public function testBin()
    {
        $platform = strtolower(PHP_OS);

        $binCheck = new BinCheck();
        $binCheck::check(Path::canonicalize($this->bin[$platform]));
    }

    public function testErrorIfRightsPermissionsAreNotSetCorrectly()
    {
        $bin = Path::canonicalize($this->bin['invalid-permissions']);

        chmod($bin, 0444);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            'Couldn\'t execute the `' . $bin . '` binary. Make sure it has the right permissions.'
        );

        $binCheck = new BinCheck();
        $binCheck::check($bin);
    }

    public function testErrorIfBinaryDontWorkCorrectly()
    {
        $platform = strtolower(PHP_OS);
        $bin = Path::canonicalize($this->bin['corrupted'][$platform]);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The `' . $bin . '` binary doesn\'t seem to work correctly');

        $binCheck = new BinCheck();
        $binCheck::check($bin);
    }
}
