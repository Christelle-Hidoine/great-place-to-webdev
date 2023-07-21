<?php

namespace App\Form\Back;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Image;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $environment = [
            'mer' => 'mer',
            'montagne' => 'montagne',
            'ville' => 'ville',
            'campagne' => 'campagne',
        ];

        $builder
            ->add('name', TextType::class,[
                "label" => "Nom de la ville",
                "attr" => [
                    "placeholder" => "Nom"
                    ]
                ]
            )
            ->add('country', EntityType::class, [
                "label" => "Pays",
                "multiple" => false,
                "expanded" => false,
                "class" => Country::class,
                "choice_label" => "name",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                    },           
                ]
            )
            ->add('area', NumberType::class, [
                "label" => "Superficie de la ville (km²)",
                "scale" => 2,
                "attr" => [
                    "placeholder" => "12000 km²"
                    ]
                ]
            )
            ->add('electricity', ChoiceType::class,[
                "label" => "Qualité du réseau électrique",
                "choices" => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                    ]
                ]
            )
            ->add('internet', ChoiceType::class,[
                "label" => "Qualité du réseau Internet",
                "choices" => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                    ]
                ]
            )
            ->add('sunshineRate', ChoiceType::class,[
                "label" => "Taux d'ensoleillement",
                "choices" => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                    ]
                ]
            )
            ->add('temperatureAverage', NumberType::class, [
                "label" => "Température moyenne en °C",
                "scale" => 1,
                "attr" => [
                    "placeholder" => "Température moyenne en °C",
                    'min' => -50,  
                    'max' => 50,   
                    ]
                ]
            )
            ->add('cost', IntegerType::class, [
                "label" => "Coût de la vie (€)",                 
                "attr" => [
                "placeholder" => "600€"
                    ]
                ]
            )
            ->add('language', LanguageType::class, [
                "label" => "Langue",
                "multiple" => false,
                "expanded" => false,
                ]
            )
            ->add('demography', IntegerType::class, [
                "label" => "Nombre d'habitants",
                "attr" => [
                    "placeholder" => "Nombre d'habitants"
                    ]
            ])
            ->add('housing', ChoiceType::class,[
                "label" => "Niveau de logement",
                "choices" => [
                    'Aucun' => "",
                    'Bas' => "low",
                    'Moyen' => "medium",
                    'Elevé' => "high",
                    ]
                ]
            )
            ->add('timezone', IntegerType::class, [
                "label" => "Fuseau horaire",
                "help" => "entre -12 et 12",
                "attr" => [
                    "placeholder" => "N° GMT",
                    'min' => -12,  
                    'max' => 12,
                    ]
                ]
            )
            ->add('environment', ChoiceType::class, [ 
                "label" => "Environnement",
                "choices" => [
                    $environment
                ],
                "expanded" => true,
                "multiple" => false, 
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
            "attr" => ["novalidate" => 'novalidate']
        ]);
    }
}
