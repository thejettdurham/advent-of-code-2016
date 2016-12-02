<?php


namespace App;


class Santa
{
    protected $heading;
    protected $position;

    /**
     * Santa constructor.
     * @param $heading
     * @param array $position
     */
    public function __construct($heading, $position)
    {
        $this->heading = $heading;
        $this->position = $position;
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

    public function moveNorth($units)
    {
        ($this->position)["y"] += $units;
    }

    public function moveSouth($units)
    {
        ($this->position)["y"] -= $units;
    }

    public function moveEast($units)
    {
        ($this->position)["x"] += $units;
    }

    public function moveWest($units)
    {
        ($this->position)["x"] -= $units;
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
}