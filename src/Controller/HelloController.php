<?php

namespace App\Controller ;

use App\Taxes\Calculator;
use App\Taxes\Detector;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController

{
   

    /**
    *@Route("/hello/{prenom}", name="hello")
     */
    public function hello($prenom = "world"){ 
       
        
        return new Response("Bonjour $prenom");

    }
}
