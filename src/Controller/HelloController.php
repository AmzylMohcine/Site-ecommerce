<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloController
{

    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }
    /**
     * @Route("/hello/{prenom?World}" , name="hello" , methods = {"GET" })
     */

    public function hello($prenom, Slugify $slugify)
    {

        dump($slugify->slugify("Hello"));

        $tva = $this->calculator->calcul(100);

        dd($tva);
        return new Response("Hello $prenom");
    }
}