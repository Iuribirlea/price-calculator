<?php
namespace App\Service;

use App\Entity\Country;
use Doctrine\Persistence\ManagerRegistry;

class PriceCalculator
{


    private ManagerRegistry $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @param float $price
     * @param string $taxNumber
     * @return float
     */
    public function calculatePrice(float $price, string $taxNumber): float
    {
        //get country tax code
        $countryCode = substr($taxNumber, 0,2);

        $country = $this->managerRegistry
            ->getRepository(Country::class)
            ->findOneBy(['countryCode' => $countryCode]);

        // calculate price based on tax rate by country
        return $price + ($price * $country->getTaxRate() / 100);
    }
}
