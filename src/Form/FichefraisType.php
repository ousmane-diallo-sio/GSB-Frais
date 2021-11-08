<?php

namespace App\Form;

use App\Entity\Fichefrais;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Query\Expr\Select;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichefraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mois', ChoiceType::class, [
                'choices' => [
                    'Janvier' => 'janv',
                    'Février' => 'févr',
                    'Mars' => 'mars',
                    'Avril' => 'avr',
                    'Mai' => 'mai',
                    'Juin' => 'juin',
                    'Juillet' => 'juill',
                    'Août' => 'août',
                    'Septembre' => 'sept',
                    'Octobre' => 'oct',
                    'Novembre' => 'nov',
                    'Décembre' => 'déc'
                ]
            ])
            ->add('nbjustificatifs')
            ->add('montantvalide')
            ->add('datemodif')
            ->add('idetat')
            ->add('idvisiteur')
            ->add('idfraisforfait')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fichefrais::class,
        ]);
    }
}
