<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Event;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ticket Name',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'placeholder' => 'Enter the ticket name',
                    'class' => 'form-control mt-2'
                ],
                'required' => false,
            ])
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'name',
                'label' => 'Event',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'placeholder' => 'Select the event',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Ticket Price',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'placeholder' => 'Enter the ticket price',
                    'class' => 'form-control mt-2'
                ],
                'required' => false,
            ])
            ->add('currency', ChoiceType::class, [
                'label' => 'Currency',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'choices' => [
                    'R$ (Real)' => 'BRL',
                    'USD (Dollar)' => 'USD',
                    '€ (Euro)' => 'EUR',
                    '¥ (Yen)' => 'JPY',
                    '£ (Pound Sterling)' => 'GBP',
                ],
                'placeholder' => 'Select the currency',
                'required' => false,
            ])
            ->add('amount', IntegerType::class, [
                'label' => 'Ticket Amount',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'placeholder' => 'Enter the ticket amount',
                    'class' => 'form-control mt-2'
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Ticket (2/2)',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
