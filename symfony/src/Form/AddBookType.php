<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class AddBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 1]),
                    new NotBlank(),
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 2]),
                    new NotBlank(),
                ]
            ])
            ->add('image')
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 13, 'max' => 13]),
                    new NotBlank(),
                ]
            ])
            ->add('Add', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            //'constraints' => [
            //    new UniqueEntity(['fields' => 'ISBN'])
            //]
        ]);
    }
}
