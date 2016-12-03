<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/2/16
 * Time: 7:55 PM
 */

namespace App;


interface ControlPanelInterface
{
    public function moveU();

    public function moveD();

    public function moveL();

    public function moveR();

    public function getPresentButton();
}