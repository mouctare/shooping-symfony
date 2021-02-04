<?php
namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{
//    protected $security;
//    protected $router;
//    protected $twig;

    // public function __construct(Security $security, RouterInterface $router, Environment $twig)
    // {
    //     $this->security = $security;
    //     $this->router = $router;
    //     $this->twig = $twig;
    // }
    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez étre connecté pour accèder à vos commandes")
     */
    public function index(){
        // 1. Nous devons nous assurer que la personne est connecté (sinon redirection vers la page d'accueil) -> Security
        /**
         * @var UserInterface
         */
         // $user = $this->security->getUser();
         $user = $this->getUser();

         // if(!$user) {
             // $url = $this->router->generate('homepage');
             // return new RedirectResponse($url);
           //  throw new AccessDeniedException("Vous devez étre connecté pour accèder à vos commandes");
              // Redirection -> RedirectResponse
              // Génerer une Url en function d'une route -> UrlGeneratorIterface ou RouterInterface
         // }
        // 2. Nous voulons savoir qui est connecté  -> Security

        // 3. Nous voulons passer l'utilisateur connécté à twig afin d'afficher ses commandes -> Environnement de twig/ Response
       return  $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        
    }
}