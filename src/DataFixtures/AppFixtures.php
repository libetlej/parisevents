<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Events

        for($i = 1; $i <= 20; $i++) {

            $event = new Event();

            $title      = $faker->sentence();
            $description = $faker->paragraph(2);
            $price      = mt_rand(0, 100);
            $namePlace  = $faker->company();
            $address    = $faker->address();
            $phone      = mt_rand(0, 99);
            $email      = $faker->companyEmail();
            $website    = 'www.website' . $i . '.com';
            $startDate  = $faker->dateTimeBetween('-1 months');
            $endDate    = $faker->dateTimeBetween($startDate, $interval = '+' . mt_rand(0, 10) . 'days');
            $image      = $faker->imageUrl();
            $createdAt  = $faker->dateTimeBetween('-2 months');

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
                    ->setCreatedAt($createdAt);

            $manager->persist($event);
        }

        $manager->flush();
    }
}
