<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Category;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture

{
    protected $slugger;
    protected $passwordHasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher)
    {
        $this->slugger = $slugger;
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $products = [];


        for ($c = 0; $c < 3; $c++) {

            $category = new Category();

            $category->setName($faker->department);
            // ->setSlug(strToLower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {

                $product = new Product();

                $product->setName($faker->productName)
                    ->setPrice($faker->price(4000, 20000))
                    // ->setSlug(strToLower($this->slugger->slug($product->getName())))
                    ->setPicture($faker->imageUrl(400, 200, true))
                    ->setShortDescription($faker->paragraph())
                    ->setCategory($category);

                $products[] = $product;


                $manager->persist($product);
            }
        }

        $admin = new User();

        $hash = $this->passwordHasher->hashPassword($admin, "password");

        $admin->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setFullName("admin")
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $users = [];

        for ($u = 0; $u <= 5; $u++) {
            $user = new User();

            $hash = $this->passwordHasher->hashPassword($user, "password");

            $user->setEmail("User$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);

            $users[] = $user;

            $manager->persist($user);
        }


        for ($p = 0; $p <= mt_rand(20, 40); $p++) {

            $purshase = new Purchase();

            $purshase->setFullName($faker->name)
                ->setAdress($faker->streetAddress)
                ->setPostaleCode($faker->postcode)
                ->setCity($faker->city)
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->dateTimeBetween('-6 months'));


            $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));

            foreach ($selectedProducts as $product) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->setProduct($product)
                    ->setQuantity(mt_rand(1, 3))
                    ->setProductName($product->getName())
                    ->setProductPrice($product->getPrice())
                    ->setTotal(
                        $purchaseItem->getProductPrice() * $purchaseItem->getQuantity()
                    )
                    ->setPurchase($purshase);

                $manager->persist($purchaseItem);
            }

            if ($faker->boolean(90)) {
                $purshase->setStatus(Purchase::STATUS_PAID);
            }

            $manager->persist($purshase);
        }



        $manager->flush();
    }
}