<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Course;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('description', TextType::class)
            ->add('startDate', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'input_format' => 'Y-m-d',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('endDate', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'input_format' => 'Y-m-d',
                'constraints' => [
                    new NotNull(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}