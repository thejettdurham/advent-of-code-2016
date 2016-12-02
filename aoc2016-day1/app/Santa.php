<?php


namespace App;


class Santa
{
    protected $heading;
    protected $position;
    protected $firstIntersectingPosition;
    protected $movementLog = [];

    /**
     * Santa constructor.
     * @param $heading
     * @param array $position
     */
    public function __construct($heading, $position)
    {
        $this->heading = $heading;
        $this->position = $position;

        $this->checkForIntersect($position);
    }

    public function executeInstruction($instruction)
    {
        $headingRotateDir = substr($instruction, 0, 1);
        $headingMoveDistance = substr($instruction, 1);

        $rotateMethod = "rotateHeading$headingRotateDir";
        $newHeading = $this->$rotateMethod();

        $moveMethod = "move$newHeading";
        $this->$moveMethod($headingMoveDistance);
    }

    /**
     * @return mixed
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @return array
     */
    public function getPosition(): array
    {
        return $this->position;
    }

    public function getFirstIntersectingPosition()
    {
        return $this->firstIntersectingPosition;
    }

    public function moveNorth($units)
    {
        foreach(range(1, $units) as $i) {
            ($this->position)["y"] += 1;
            $this->checkForIntersect($this->position);
        }
    }

    public function moveSouth($units)
    {
        foreach(range(1, $units) as $i) {
            ($this->position)["y"]--;
            $this->checkForIntersect($this->position);
        }

    }

    public function moveEast($units)
    {
        foreach(range(1, $units) as $i) {
            ($this->position)["x"]++;
            $this->checkForIntersect($this->position);
        }
    }

    public function moveWest($units)
    {
        foreach(range(1, $units) as $i) {
            ($this->position)["x"]--;
            $this->checkForIntersect($this->position);
        }
    }

    public function rotateHeadingL()
    {
        switch($this->heading) {
            case "North":
                $this->heading = "West";
                break;
            case "South":
                $this->heading = "East";
                break;
            case "East":
                $this->heading = "North";
                break;
            case "West":
                $this->heading = "South";
                break;
        }

        return $this->heading;
    }

    public function rotateHeadingR()
    {
        switch($this->heading) {
            case "North":
                $this->heading = "East";
                break;
            case "South":
                $this->heading = "West";
                break;
            case "East":
                $this->heading = "South";
                break;
            case "West":
                $this->heading = "North";
                break;
        }

        return $this->heading;
    }

    private function checkForIntersect($position)
    {
        if (!$this->firstIntersectingPosition) {
            $positionKey = json_encode($position);
            if (!array_key_exists($positionKey, $this->movementLog)) {
                $this->movementLog[$positionKey] = 0;
            }

            $this->movementLog[$positionKey]++;

            if ($this->movementLog[$positionKey] > 1) {
                $this->firstIntersectingPosition = $position;
            }
        }
    }
}