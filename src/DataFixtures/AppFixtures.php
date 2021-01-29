<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Category;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;


    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

     

        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "password");
        $admin->setEmail("admind@yahoo.fr")
             ->setPassword($hash)
             ->setFullName("Admin")
             ->setRoles(['ROLE_ADMIN']);

             $manager->persist($admin);

             $users = [];

       for($u = 0; $u < 5; $u++){
         $user = new User();


         $hash = $this->encoder->encodePassword($user, "password");

         $user->setEmail("user$u@yahoo.fr")
             ->setFullName($faker->name())
             ->setPassword($hash);
        $users[] = $user;
             $manager->persist($user);
       }


         $products = [];


        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName("meuble")
          ->setSlug(strtolower($this->slugger->slug($category->getName()
        )));
            $manager->persist($category);


            for ($p = 0; $p < mt_rand(15, 20); $p++) {
              $product = new Product();
        
              $product->setName($faker->sentence())
              ->setPrice($faker->price(4000, 20000))
             ->setSlug(strtolower($this->slugger->slug($product->getName())))
            ->setCategory($category)
              ->setShortDescription($faker->paragraph())
              ->setMainPicture($faker->imageUrl(400, 400, true));
              $products[] = $product;

              $manager->persist($product);
            }
        }

        for ($p = 0; $p < mt_rand(20, 40); $p++){
          $purchase = new Purchase;

          $purchase->setFullName($faker->name)
                   ->setAddress($faker->streetAddress)
                   ->setPostalCode($faker->postcode)
                   ->setCity($faker->city)
                   ->setUser($faker->randomElement($users)) // randomElement permet de trouver un element dans un tableau
                   ->setTotal(\mt_rand(2000, 30000))
                   ->setPurchasedAt($faker->datetimeBetween('-6 months'));
                   // Ici je met le tableau de mes produits crée en haut dans la variable  $selectedProducts
                   // Ensuite je récupère dans ce tableau entre 3 et 5 commandes
                   $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));
                   //Donc
                   foreach($selectedProducts as $product){
                     // Grace à la relation plusieurs à plusierus j'ai une methode addProduct
                     $purchaseItem = new PurchaseItem;
                     // Ici je crée une ligne de commande ou tiket de caise
                     $purchaseItem->setProduct($product)
                                  ->setQuantity(mt_rand(1, 3))
                                  ->setProductName($product->getName())
                                  ->setProductPrice($product->getPrice())
                                  ->setTotal($purchaseItem->getProductPrice() * $purchaseItem->getQuantity())
                                  ->setPurchase($purchase);
                                  $manager->persist($purchaseItem);
                   }
                   if($faker->boolean(50)){
                     $purchase->setStatus(Purchase::STATUS_PAID);
                   }
                   $manager->persist($purchase);
        }
    

    $manager->flush();
}
}
    
