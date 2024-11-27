<?php

namespace App\Form;

use App\Entity\PurchaseTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/*
 * Form builder to create a form for purchasing a ticket
 */
class PurchaseTicketType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('quantity', ChoiceType::class, [
                'label' => 'Quantity',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Purchase Ticket',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => PurchaseTicket::class,
        ]);
    }
}
