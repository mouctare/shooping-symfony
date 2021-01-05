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
    public function hello($prenom = "world", LoggerInterface $logger, Calculator $calculator, Environment $twig, Detector $detector){ 
        dump($detector->detect(101));
        dump($detector->detect(10));

       // dump($twig);
        // il ya plusierus façon de se faire livrer un ijection de dependance ici je l'utilise directement dans ma function
        $logger->info("Mon Message de log");
        $tva = $calculator->calcul(100);
        dd($tva);
        return new Response("Bonjour $prenom");

    }
}
