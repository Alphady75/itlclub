<?php

namespace App\Form;

use App\Entity\SearchOffres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class SearchOffresForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('q', TextType::class, [
				'label' =>  false,
				'required'  =>  false,
				'attr'  =>  [
					'placeholder'   =>  'Je cherche ...'
				]
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => SearchOffres::class,
			'method' => 'GET',
			'csrf_protection' => false,
		]);
	}

	public function getBlockPrefix()
	{
		return '';
	}
}
