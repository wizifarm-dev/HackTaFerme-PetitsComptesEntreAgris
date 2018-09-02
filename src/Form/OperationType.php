<?php

namespace App\Form;

use App\Entity\Operation;
use App\Entity\Resource;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $logggedUser */
        $logggedUser = $options['user'];

        // shitty code
        $limitResourcesToUsers = [];
        foreach ($logggedUser->getTeams() as $team) {
            foreach ($team->getUsers() as $user) {
                $limitResourcesToUsers[$user->getId()] = $user;
            }
        }

        $builder
            ->add('date', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('resource', EntityType::class, [
                'class' => Resource::class,
                'required' => true,
                'placeholder'=> 'Type de ressource',
                'query_builder' => function (EntityRepository $er) use ($limitResourcesToUsers) {
                    return $er->createQueryBuilder('r')
                        ->where('r.owner IN (:users)')
                        ->setParameter('users', $limitResourcesToUsers);
                },
            ])
            ->add('quantity', IntegerType::class, [
                'required' => true,
            ])
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($logggedUser) {
                    return $er->createQueryBuilder('t')
                        ->where(':user MEMBER OF t.users')
                        ->setParameter('user', $logggedUser);
                },
                'placeholder'=> 'Groupe',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($logggedUser) {
                    return $er->createQueryBuilder('u')
                        ->where('u != :user')
                        ->setParameter('user', $logggedUser);
                },
                'placeholder'=> 'Bénéficiaire',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
        $resolver->setRequired('user');
    }
}
