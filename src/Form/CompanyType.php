<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'The name field cannot be blank'
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'The name must be at least {{ limit }} characters long'
                    ]),
                ],
            ])
            ->add('cnpj', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'The CNPJ field cannot be blank'
                    ]),
                    new Assert\Length([
                        'min' => 14,
                        'max' => 14,
                        'exactMessage' => 'The CNPJ must be exactly {{ limit }} characters long'
                    ]),
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'csrf_protection' => false,
        ]);
    }
}
