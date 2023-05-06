<?php

namespace App\Form;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', ChoiceType::class, [
            'choices' => $this->getProductChoices(),
            'label' => 'Product Name',
        ])
            ->add('taxNumber', TextType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Calculate'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    /**
     * @return array
     */
    private function getProductChoices(): array
    {
        $products = $this
            ->entityManager
            ->getRepository(Product::class)
            ->findAll();

        $choices = [];
        foreach ($products as $product) {
            $choices[$product->getName()] = $product->getId();
        }

        return $choices;
    }

}
