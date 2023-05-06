<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->addProductFixtures();
        $this->addCountryFixtures();
    }

    /**
     * @return void
     */
    private function addCountryFixtures(): void {

        // Create products
        $headphones = new Product();
        $headphones->setName('Headphones')
            ->setPrice(100.0);

        $phoneCase = new Product();
        $phoneCase->setName('Phone case')
            ->setPrice(20.0);

        // Persist the products to the database
        $this->manager->persist($headphones);
        $this->manager->persist($phoneCase);

        // Flush the changes to the database
        $this->manager->flush();
    }

    /**
     * @return void
     */
    private function addProductFixtures(): void {

        $germany = new Country();
        $germany->setName('Germany')
            ->setCountryCode('DE')
            ->setTaxRate(19);

        $italy = new Country();
        $italy->setName('Italy')
            ->setCountryCode('IT')
            ->setTaxRate(22);

        $greece = new Country();
        $greece->setName('Greece')
            ->setCountryCode('GR')
            ->setTaxRate(24);

        $this->manager->persist($germany);
        $this->manager->persist($italy);
        $this->manager->persist($greece);

        $this->manager->flush();

    }
}
