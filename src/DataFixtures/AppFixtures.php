<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        //Role
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // User Admin
        $adminUser = new User();
        $adminUser  ->setFirstName('Jesse')
                    ->setLastName('Pinkman')
                    ->setEmail('jesse@mail.com')
                    ->setPassword($this->encoder->encodePassword($adminUser,'123456'))
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Users
        $users =[];
        // Definir le genre de l'utilisateur pour le prènom et l'avatar
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 10; $i++) {

            $user = new User();

            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;

            $password = $this->encoder->encodePassword($user, 'password');

            $user   ->setFirstName($faker->firstName($genre))
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setPassword($password)
                    ->setAvatar($avatar);

            $manager->persist($user);
            // On place l'ensemble des User crée dans le tableau $users[]
            $users[] = $user;
        }


        // Category

        $categories = [];
        $categoriesName = ['spectacle', 'exposition', 'concert', 'théâtre', 'soirée'];

        foreach ($categoriesName as $name) {

            $category = new Category();
            $category->setName($name);

            $manager->persist($category);
            $categories[] = $category;
        }


        // Events

        for($i = 1; $i <= 20; $i++) {

            $event = new Event();

            $title      = $faker->sentence();
            $description = $faker->paragraph(2);
            $price      = mt_rand(0, 100);
            $namePlace  = $faker->company();
            $address    = $faker->address();
            $phone      = mt_rand(0, 9) . mt_rand(0, 99) . mt_rand(0, 99) . mt_rand(0, 99) . mt_rand(0, 99);
            $email      = $faker->companyEmail();
            $website    = 'www.website' . $i . '.com';
            $startDate  = $faker->dateTimeBetween('-1 months');
            $endDate    = $faker->dateTimeBetween($startDate, $interval = '+' . mt_rand(0, 10) . 'days');
            $image      = 'img' . mt_rand(1, 6) . '.jpg';
            $createdAt  = $faker->dateTimeBetween('-2 months');
            // Selectionner au hasard à partir du tableau des users un user
            $user       = $users[mt_rand(0, count($users) - 1)];
            $category   = $categories[mt_rand(0, count($categories) - 1)];

            $event  ->setTitle($title)
                    ->setDescription($description)
                    ->setPrice($price)
                    ->setNamePlace($namePlace)
                    ->setAddress($address)
                    ->setPhone($phone)
                    ->setEmail($email)
                    ->setWebsite($website)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setImage($image)
                    ->setCreatedAt($createdAt)
                    ->setUser($user)
                    ->setCategory($category);

            $manager->persist($event);
        }

        $manager->flush();
    }
}
