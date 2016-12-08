<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $addresses = $this->parseInputAsRowsFromFile();

        $addressesSupportingTls = [];

        $addressesSupportingSsl = [];
        $i = 0;
        foreach ($addresses as $address) {

            $hypernetHasAbba = false;
            $abasInSupernet = [];
            $abasInHypernet = [];
            preg_match_all('#\[(.*?)\]#', $address, $hypernets);

            foreach ($hypernets[1] as $hypernet) {
                if ($this->partContainsAbba($hypernet)) {
                    $hypernetHasAbba = true;
                }

                $abas = $this->getAbasForPart($hypernet);

                $abasInHypernet = array_merge($abasInHypernet, $abas);
            }

            //if (count($babsInHypernet) > 0) dd($babsInHypernet);
            //if ($hypernetHasAbba) continue;

            preg_match_all('#^(.*?)\[|\](.*?)\[|\](.*?)$#', $address, $supernets);

            foreach ($supernets[0] as $supernet) {
                $cleanedPart = str_replace(["[" , "]"], "", $supernet);
                $abas = $this->getAbasForPart($cleanedPart);
                $abasInSupernet = array_merge($abasInSupernet, $abas);


                if (!$hypernetHasAbba && $this->partContainsAbba($cleanedPart)) {
                    $addressesSupportingTls[$i] = $address;
                }

            }

            if (count($abasInSupernet) > 0 && count($abasInHypernet) > 0 && $this->addressHasBabInHypernet($abasInHypernet, $abasInSupernet)) {
                $addressesSupportingSsl[$i] = $address;
            }

            $i++;
        }

        $num = count($addressesSupportingTls);
        $this->info("Part 1: $num addresses support TLS");

        asort($addressesSupportingSsl);
        file_put_contents("part2results-wrong.txt", implode(PHP_EOL, $addressesSupportingSsl));
        $num2 = count($addressesSupportingSsl);
        $this->info("Part 2: $num2 addresses support SSL");
    }

    protected function partContainsAbba($part) {
        $numMatches = preg_match_all("/(.)(.)(\\2)(\\1)/", $part, $matches);

        if ($numMatches > 0) {
            foreach ($matches[0] as $match) {
                $chars = str_split($match);
                if ($chars[0] === $chars[1]) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    protected function parseInputAsRowsFromFile()
    {
        $inputs = file_get_contents("input.txt");
        return explode("\n", $inputs);
    }

    private function getAbasForPart($part)
    {
        $partChars = str_split($part);

        $abas = [];

        $part2 = "";
        for ($i = 0; $i < count($partChars) - 2; $i++) {
            $part2 .= $partChars[$i];
            if ($partChars[$i] === $partChars[$i+2] && $partChars[$i] !== $partChars[$i+1]) {
                $abas[] = $partChars[$i].$partChars[$i+1].$partChars[$i+2];
            }
        }

        return $abas;
    }

    private function addressHasBabInHypernet($abasInHypernet, $abasInSupernet)
    {
        foreach($abasInSupernet as $aba) {
            foreach($abasInHypernet as $bab) {
                $babParts = str_split($bab);
                $bab = $babParts[1].$babParts[0].$babParts[1];

                if ($aba === $bab) {
                    return true;
                }
            }
        }

        return false;
    }
}