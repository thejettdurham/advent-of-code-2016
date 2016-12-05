<?php

namespace App\Events;

class GotPasswordCharacter extends Event
{
    protected $hash;
    protected $index;

    /**
     * HashJobDone constructor.
     * @param $hash
     * @param $index
     */
    public function __construct($hash, $index)
    {
        $this->hash = $hash;
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

}
