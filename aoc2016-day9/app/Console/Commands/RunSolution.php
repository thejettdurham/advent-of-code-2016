<?php


namespace App\Console\Commands;


use App\Screen;
use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $compressed = $this->parseInputFromFile();

        $uncompressed = $this->decompress($compressed);


        $part1length = strlen($uncompressed);
        $part2length = $this->getVer2DecompressedLength($compressed);
        $this->info("Part 1: uncompressed is $part1length long");
        $this->info("Part 2: uncompressed is $part2length long");
    }

    protected function parseInputFromFile()
    {
        return file_get_contents("input.txt");
    }

    protected function decompress($compressed) {
        $uncompressed = "";

        while(true) {
            $parts = explode("(", $compressed, 2);

            if (!(count($parts) > 1)) break;
            // This allows for input to have uncompressed text before the next marker
            $uncompressed .= $parts[0];

            $compressed = $parts[1];
            $toUncompressParts = explode(")", $compressed, 2);

            $uncompressInstruction = $toUncompressParts[0];
            $compressed = $toUncompressParts[1];

            $uncompressParts = explode("x", $uncompressInstruction);
            $uncompressLength = $uncompressParts[0];
            $uncompressRepeats = $uncompressParts[1];

            $uncompressed .= str_repeat(substr($compressed, 0, $uncompressLength), $uncompressRepeats);
            $compressed = substr($compressed, $uncompressLength);

        }

        return $uncompressed;
    }

    private function getVer2DecompressedLength($compressed)
    {
        $length = 0;

        while(true) {
            $parts = explode("(", $compressed, 2);

            $length += strlen($parts[0]);
            if (!(count($parts) > 1)) return $length;

            $compressed = $parts[1];
            $toUncompressParts = explode(")", $compressed, 2);

            $uncompressInstruction = $toUncompressParts[0];
            $compressed = $toUncompressParts[1];


            $uncompressParts = explode("x", $uncompressInstruction);
            $uncompressLength = $uncompressParts[0];
            $uncompressRepeats = $uncompressParts[1];

            $toUncompress = substr($compressed, 0, $uncompressLength);
            $compressed = substr($compressed, $uncompressLength);
            $length += $uncompressRepeats * $this->getVer2DecompressedLength($toUncompress);

        }
    }
}