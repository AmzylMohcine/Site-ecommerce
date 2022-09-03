<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{

    public function index()
    {
        var_dump("Ca fonctionne");
        die;
    }
    /** 
     * @Route("/test/{age<\d+>?0}" , name ="test" , methods = {"GET" , "POST"}  , hotes = "localhost" , schemes = {"http" , "https"})
     */

    public function test(Request $request)
    {
        dump($request);
        $age = $request->attributes->get('age');

        return new Response("vous avez $age ans ");
    }
}