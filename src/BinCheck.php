<?php
namespace BinCheck;

use Webmozart\PathUtil\Path;

class BinCheck
{
    public static function check($bin, $args = ['--help'])
    {
        $bin = Path::canonicalize($bin);

        $isExecutable = is_executable($bin);

        if (!$isExecutable) {
            throw new \Exception(
                'Couldn\'t execute the `' . $bin . '` binary. Make sure it has the right permissions.'
            );
        }

        exec($bin . ' ' . implode(' ', $args), $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('The `' . $bin . '` binary doesn\'t seem to work correctly');
        }
    }
}
