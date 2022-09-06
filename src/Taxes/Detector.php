<?php

namespace App\Taxes;


class Detector
{
    protected $seuil;

    public function __construct(float $seuil)
    {
        $this->seuil = $seuil;
    }

    public function detector($number): bool
    {
        if ($number > $this->seuil) {
            return True;
        } else {
            return False;
        }
    }
}