<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Event Name',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'placeholder' => 'Enter the event name',
                    'class' => 'form-control mt-2'
                ],
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'label' => 'Event Description',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'placeholder' => 'Enter the event description',
                    'class' => 'form-control mt-2'
                ],
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'placeholder' => 'Enter the event location',
                    'class' => 'form-control mt-2'
                ],
                'required' => false,
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End Date',
                'label_attr' => [
                    'class' => 'fw-bold'
                ],
                'attr' => [
                    'class' => 'form-control mt-2',
                ],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Start Time',
                'label_attr' => [
                    'class' => 'fw-bold',
                ],
                'attr' => [
                    'class' => 'form-control mt-2',
                ],
                'required' => false,
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'End Time',
                'label_attr' => [
                    'class' => 'fw-bold',
                ],
                'attr' => [
                    'class' => 'form-control mt-2',
                ],
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create Event (1/2)',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
