<?php


namespace App\Console\Commands;


use App\Screen;
use Illuminate\Console\Command;
use Prophecy\Exception\Prediction\FailedPredictionException;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    // Input modeled as coordinates pairing the floor of a unit's generator with the floor of its microchip
    protected $initialState = [
        1, [1,1], [1,2], [1,2], [3,3], [3,3], [1,1], [1,1]
    ];

    protected $desiredState = [
        4, [4,4], [4,4], [4,4], [4,4], [4,4], [4,4], [4,4]
    ];

    // HashTable of visited states
    protected $visitedStates = [];

    public function fire()
    {
        $steps = 0;

        $states = [$this->initialState];
        while (true) {
            $this->info("$steps steps done, checking ". count($states) ." states with ". count($this->visitedStates)." visited states");
            $steps++;
            $newStates = [];
            foreach($states as $state) {

                try {
                    $nextStates = $this->getLegalNextStates($state);

                // Too lazy to implement a custom exception, this just means a legal state was found that matches the desired state
                } catch (FailedPredictionException $ex) {
                    //$this->info($ex->getMessage());

                    break 2;
                }

                $newStates = array_merge($nextStates, $newStates);

            }

            $states = $newStates;
            if (count($newStates) == 0) {
                throw new \Exception("No possible path to destination");
            }
        }

        $this->info("Part 1: Got the desired state in $steps steps");
    }

    /**
     * @param $state
     * @return array
     */
    private function getLegalNextStates($state)
    {
        $legalStates = [];
        $startingFloor = array_shift($state);

        if ($startingFloor < 4) {
            $legalStates = array_merge($legalStates, $this->getLegalStatesForState($state, $startingFloor, $startingFloor + 1));
        }

        $allPartFloors = array_merge(array_column($state, 0), array_column($state,1));

        if ($startingFloor > 1) {
            if (in_array($startingFloor - 1, $allPartFloors)) {
                $legalStates = array_merge($legalStates, $this->getLegalStatesForState($state, $startingFloor, $startingFloor - 1));
            }
        }


        return $legalStates;
    }


    /**
     * @param $state
     * @return bool
     */
    public function stateIsLegal($state)
    {
        $gens = array_column($state, 0);
        $chips = array_column($state, 1);

        foreach(range(1,4) as $floorNum) {
            $floorGens = [];
            $floorChips = [];

            foreach($gens as $i => $gen) {
                if ($gen == $floorNum) {
                    $floorGens[$i] = $gen;
                }
            }

            if (count($floorGens) == 0) continue;

            foreach($chips as $i => $chip) {
                if ($chip == $floorNum) {
                    $floorChips[$i] = $chip;
                }
            }

            foreach($floorChips as $i => $chip) {
                if (!isset($floorGens[$i])) return false;
            }
            //if (count(array_intersect(array_keys($floorChips), array_keys($floorGens))))
        }

        return true;
    }

    private function getLegalStatesForState($state, $startingFloor, $newFloor)
    {
        $tryStates = [];
        $legalStates = [];
        foreach($state as $i => $unit) {
            if (in_array($startingFloor, $unit)) {
                if ($unit[0] == $startingFloor) {
                    $tryState = $state;
                    $tryState[$i] = [$newFloor, $unit[1]];
                    if ($this->visitNewState($tryState, $newFloor)) $tryStates[] = $tryState;
                }

                if ($unit[1] == $startingFloor) {
                    $tryState = $state;
                    $tryState[$i] = [$unit[0], $newFloor];
                    if ($this->visitNewState($tryState, $newFloor)) $tryStates[] = $tryState;
                }

                if ($unit[0] == $startingFloor && $unit[1] == $startingFloor) {
                    $tryState = $state;
                    $tryState[$i] = [$newFloor, $newFloor];
                    if ($this->visitNewState($tryState, $newFloor)) $tryStates[] = $tryState;
                }

                for($j = $i+1; $j < count($state); $j++) {
                    $cmpUnit = $state[$j];
                    if (in_array($startingFloor, $cmpUnit)) {
                        if ($unit[0] == $startingFloor) {
                            $tryState = $state;
                            $tryState[$i] = [$newFloor, $unit[1]];

                            if ($cmpUnit[0] == $startingFloor) {
                                $tryState[$j] = [$newFloor, $cmpUnit[1]];
                                if ($this->visitNewState($tryState, $newFloor)) $tryStates[] = $tryState;
                            }

                            if ($cmpUnit[1] == $startingFloor) {
                                $tryState[$j] = [$cmpUnit[0], $newFloor];
                                if ($this->visitNewState($tryState, $newFloor)) $tryStates[] = $tryState;
                            }
                        }

                        if ($unit[1] == $startingFloor) {
                            $tryState = $state;
                            $tryState[$i] = [$unit[0], $newFloor];

                            if ($cmpUnit[0] == $startingFloor) {
                                $tryState[$j] = [$newFloor, $cmpUnit[1]];
                                if ($this->visitNewState($tryState, $newFloor)) $tryStates[] = $tryState;
                            }

                            if ($cmpUnit[1] == $startingFloor) {
                                $tryState[$j] = [$cmpUnit[0], $newFloor];
                                if ($this->visitNewState($tryState, $newFloor)) $tryStates[] = $tryState;
                            }
                        }
                    }
                }
            }

        }

        foreach($tryStates as $tryState) {
            if ($this->stateIsLegal($tryState)) {
                $legalState = array_merge([$newFloor], $tryState);
                if ($legalState == $this->desiredState) {
                    throw new FailedPredictionException(serialize($legalState));
                }
                $legalStates[] = $legalState;
            }
        }

        return $legalStates;
    }

    private function visitNewState($state, $newFloor)
    {
        // Normalize States
        sort($state);
        $stateKey = json_encode([$newFloor, $state]);
        if (!isset($this->visitedStates[$stateKey])) {
            $this->visitedStates[$stateKey] = 0;
            return true;
        }
        return false;
    }
}