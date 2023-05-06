<?php

namespace App\Controller;

use App\Service\PriceCalculator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\PriceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceController extends AbstractController
{

    private ManagerRegistry $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route(
        path: '/',
        name: 'app_price',
        methods: ['GET', 'POST']
    )]
    public function calculatePrice(Request $request, PriceCalculator $calculator): Response
    {
        $form = $this->createForm(PriceType::class);

        $form->handleRequest($request);
        $price = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $productRepository = $this
                ->managerRegistry
                ->getRepository(Product::class);

            /**
             * @var Product $product;
             * @var Product $formData;
             */
            $formData = $form->getData();
            // find selected product in DB
            $product = $productRepository
                ->find($formData->getName());
            if (!$product) {
                $this->addFlash('error', 'Request error');
            } else {
                $price = $calculator->calculatePrice(
                    $product->getPrice(),
                    $formData->getTaxNumber()
                );
            }
        }

        return $this->render('price/calculateForm.html.twig', [
            'productForm' => $form->createView(),
            'price' => $price,
        ]);
    }

}
