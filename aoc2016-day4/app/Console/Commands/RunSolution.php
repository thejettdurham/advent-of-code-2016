<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $input = $this->parseInputAsRowsFromFile();

        $validRoomSectorIds = [];
        $northPoleStorageSectorId = "";
        foreach($input as $i) {
            $encrypted = substr($i, 0, strrpos($i, "-"));
            $sectorId = substr($i, strrpos($i, "-") + 1, 3);
            $checksum = substr($i, strpos($i, "[") + 1, 5);

            $substrCounts = [];
            foreach (range('a', 'z') as $char) {
                $substrCounts[$char] = substr_count($i, $char);
            }

            array_multisort(array_values($substrCounts), SORT_DESC, array_keys($substrCounts), SORT_ASC, $substrCounts);
            $keys = array_keys($substrCounts);
            $keysStr = implode("", $keys);

            $calculatedChecksum = substr($keysStr, 0, 5);
            if ($calculatedChecksum === $checksum) {
                $validRoomSectorIds[] = $sectorId;

                $decrypted = "";
                foreach(str_split($encrypted) as $char)
                {
                    if ($char === "-") {
                        $decryptedChar = " ";
                    } else {
                        $normalizedCharVal = ord($char) - 97;
                        $decryptedNormalized = ($normalizedCharVal + $sectorId) % 26;
                        $actualDecryptedChar = $decryptedNormalized + 97;

                        $decryptedChar = chr($actualDecryptedChar);
                    }

                    $decrypted .= $decryptedChar;
                }

                // This makes a pretty broad assumption, but since the exact location name isn't specified by the challenge,
                // I had to guess what it might be. YMMV!
                if (strpos($decrypted, 'pole') !== false) {
                    $northPoleStorageSectorId = $sectorId;
                }

            }
        }

        $sectorIdSum = array_sum($validRoomSectorIds);

        $this->info("Sum of SectorIDs from valid rooms is $sectorIdSum");
        $this->info("Sector Id of North Pole Object Store: $northPoleStorageSectorId");
    }

    protected function parseInputAsRowsFromFile()
    {
        $inputs = file_get_contents("input.txt");
        return explode("\n", $inputs);
    }
}