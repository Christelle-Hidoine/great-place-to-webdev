<?php

namespace App\Form\Front;

use App\Data\FilterData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $visas = [
            'Visa touriste' => 'Visa touriste',
            'Visa nomade' => 'Visa nomade',
            'Virtual Working Program'=> 'Virtual Working Program',
            'Spécial Tourist Visa' => 'Spécial Tourist Visa',
            'Welcome Stamp' => 'Welcome Stamp',
            'Residencia Temporal Empleados Especializados por cuenta propia' => 'Residencia Temporal Empleados Especializados por cuenta propia',
            'Work From Bermuda' => 'Work From Bermuda',
            'Long-term visa for remote workers and family' => 'Long-term visa for remote workers and family',
            'e-Visa' => 'e-Visa',
            'Antigua Nomad Digital Residence' => 'Antigua Nomad Digital Residence',
            'Global Citizen Concierge' => 'Global Citizen Concierge',
            'visa Premium' => 'visa Premium',
        ];

        $environment = [
            'mer' => 'mer',
            'montagne' => 'montagne',
            'ville' => 'ville',
            'campagne' => 'campagne',
        ];

        $builder
            ->add('electricityLevel', ChoiceType::class, [
                'choices' => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                ],
                'label' => false,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('internetLevel', ChoiceType::class, [
                'choices' => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                ],
                'label' => false,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('sunshineLevel', ChoiceType::class, [
                'choices' => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                ],
                'label' => false,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('housingLevel', ChoiceType::class, [
                'choices' => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                ],
                'label' => false,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('temperatureMin', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'min °C',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('temperatureMax', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'max °C',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('demographyMin', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'min',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('demographyMax', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'max',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('costMin', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'min €',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('costMax', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'max €',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('areaMin', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'min km²',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('areaMax', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'max km²',
                    'class' => 'text-sm w-20 rounded-lg',
                ]
            ])
            ->add('currencyType', CurrencyType::class, [
                "choices" => [
                    "expanded" => true, 
                    "multiple" => false],
                    'label' => false,
                    'placeholder' => 'Sélectionner', 'required' => true,
            ])
            ->add('timezone', NumberType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'N° GMT',
                    'class' => 'text-sm w-20 rounded-lg',
                ],
            ])
            ->add('visaType', ChoiceType::class, [ 
                "choices" => [
                    $visas
                    ],
                "expanded" => true,
                "multiple" => false,
                'label' => false,
            ])
            ->add('visaRequired', CheckboxType::class, [
                "label" => "Visa Requis",
                "required" => false,
            ])
            ->add('language', LanguageType::class, [
                "choices" => [
                    "expanded" => false, 
                    "multiple" => false],
                    'label' => false,
                    'placeholder' => 'Sélectionner', 'required' => true,
            ])
            ->add('environment', ChoiceType::class, [ 
                "choices" => [
                    $environment
                    ],
                "expanded" => true,
                "multiple" => false,
                'label' => false,
            ])
        ;          
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class,
            'method' => 'GET',
            'attr' => ['id' => 'filter_data']
        ]);
    }
}