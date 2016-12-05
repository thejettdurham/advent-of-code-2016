<?php


namespace App;


use Illuminate\Support\Facades\Cache;

class PasswordHashCache
{
    protected $hashes = [];
    protected $securePassword = "________";

    /**
     * @return mixed
     */
    public function getHashes()
    {
        return $this->hashes;
    }

    public function pushHash($hash, $index)
    {
        $this->hashes[$index] = $hash;

        Cache::tags("aoc-day5")->forever("passwordHashes", json_encode($this->hashes));
    }

    public function __construct() {
        if (Cache::tags("aoc-day5")->has("passwordHashes")) {
            $this->hashes = json_decode(Cache::tags("aoc-day5")->get("passwordHashes"), true);
        }
    }

    public function hasSufficientHashesToCrackPassword() {
        return (count($this->hashes) >= 8);
    }

    public function hasCrackedMoreSecurePassword() {
        return strpos($this->getMoreSecureCrackedPassword(), '_') === false;
    }

    public function getCrackedPassword() {
        $password="";
        foreach($this->hashes as $hash) {
            $password .= str_split($hash)[5];
        }

        return substr($password, 0, 8);
    }

    public function getMoreSecureCrackedPassword() {
        $securePassParts = str_split($this->securePassword);
        foreach($this->hashes as $hash) {
            $hashChars = str_split($hash);

            if (array_key_exists($hashChars[5], $securePassParts) && $securePassParts[$hashChars[5]] === '_') {
                $securePassParts[$hashChars[5]] = $hashChars[6];
            }
        }

        $this->securePassword = implode($securePassParts);

        return $this->securePassword;
    }

    public function allCracked() {
        return !empty($this->hashes) && $this->hasSufficientHashesToCrackPassword() && $this->hasCrackedMoreSecurePassword();
    }
}