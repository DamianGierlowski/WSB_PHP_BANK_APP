<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ammount', NumberType::class)
            ->add('recipient',EntityType::class ,['class' => Account::class,
                'query_builder' => function (AccountRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.number', 'ASC');
                },
                'choice_label' => 'number',])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
