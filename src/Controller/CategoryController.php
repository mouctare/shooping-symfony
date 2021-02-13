<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CategoryController extends AbstractController
{
    protected $categoryRepository;
    

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
   public function renderMenuList(){
       // 1. Aller chercher les catégories dans la base de données (repository)
       $categories = $this->categoryRepository->findAll();

       //2.Renvoyer le rendu HTML  sous la forme d'une Response ($this->render)
       return $this->render('category/_menu.html.twig', [
           'categories' => $categories
       ]);
   }




    /**
     * @Route("admin/category/create", name="category_create")
     */
    public function create(Request $request,  EntityManagerInterface $em ,  SluggerInterface $slugger)
    {
    $category = new Category;

    $form = $this->createForm(CategoryType::class, $category);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $category->setSlug(strtolower($slugger->slug($category->getName())
    ));

        $em->persist($category);

        $em->flush();

        return $this->redirectToRoute('homepage');
    }


     $formView = $form->createView();

return $this->render('category/create.html.twig', [
     "formView" => $formView
 ]);
    }

   /**
     * @Route("admin/category/{id}/edit", name="category_edit")
     */
    public function edit($id, CategoryRepository $categoryRepository,  Request $request,  EntityManagerInterface $em, Security $security)
    {
        // $user = $security->getUser();
        // On peut la methode directeemne t dans la route  @IsGranted("ROLE_ADMIN ", message="Vous n'avez pas le droit  d'accéder à cette ressource")
        // if($user === null){
        //     return $this->redirectToRoute('security_login');
        // }
        // if(!in_array("ROLE_ADMIN", $user->getRoles())){
        //     throw new AccessDeniedHttpException("Vous n'avez pas le droit  d'accéder à cette ressource");
        // }

        //$user = $security->getUser();
        // grace à l'abstract controller on peut faire et pas besoin de se faire livrer le Security $security

        // $user = $this->getUser();
        //    if($user === null){
        //      return $this->redirectToRoute('security_login');
        //  }

        // if($this->isGranted("ROLE_ADMIN") === false){
        //     throw new AccessDeniedHttpException("Vous n'avez pas le droit  d'accéder à cette ressource");  
        // }
        
        
       // $this->denyAccessUnlessGranted("ROLE_ADMIN ", null, "Vous n'avez pas le droit  d'accéder à cette ressource");




        $category =  $categoryRepository->find($id);

        if(!$category){
            throw new NotFoundHttpException("Cette catégorie n'éxiste pas");
        }

       // $user = $this->getUser(); // $security->getUser()

      //  if(!$user){
       //     return $this->redirectToRoute('security_login');
      //  }
        // Je verifie Es ce que l'utilisateur c'est le meme qui a crée la catégorie
      //  if($user !== $category->getOwner()) {
        //    throw new AccessDeniedHttpException("Vous n'etes pas le propriètaire de cette catégorie "); 

      //  }
      //$security->isGranted('CAN_EDIT', $category);
      //$this->denyAccessUnlessGranted('CAN_EDIT', $category, "Vous n'etes pas le propriètaire de cette catégorie ");

        $form = $this->createForm(CategoryType::class, $category); 

        $form->handleRequest($request);
        
       if ($form->isSubmitted() && $form->isValid()) {
       
          $em->flush();

       return $this->redirectToRoute('homepage');

       }
       $formView = $form->createView();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
            
        ]);
    }
}
