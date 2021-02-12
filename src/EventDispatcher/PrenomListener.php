<?php
namespace App\EventDispather;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class PrenomListener {
    public function addPrenomToAttributes(RequestEvent $requestEvent){
        //dd($requestEvent);
        $requestEvent->getRequest()->attributes->set('prenom', 'Lior');

       // dd($requestEvent->getRequest());
    }
}