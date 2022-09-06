<?php

namespace App\Controller;

use LDAP\Result;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    // protected $logger;

    // public function __construct(LoggerInterface $logger)
    // {

    //     $this->logger = $logger;
    // }
    /**
     * @Route("/" , name="index" )
     */

    public function index()
    {

        return new Response("Ca fonctionne");
    }
    /** 
     * @Route("/test/{age<ar
     * \d+>?0}" , name ="test" , methods= {"GET" , "POST"}  , host= "localhost" , schemes= {"http" , "https"})
     */

    public function test(Request $request)
    {
        dump($request);
        $age = $request->attributes->get('age');

        return new Response("vous avez $age ans ");
    }
}