<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		if (in_array('ROLE_ADMIN', $options['role'])) {
            // do as you want if admin
            $builder
			->add('ref')
			->add('name')
			->add('image')		
			->add('userid')
			;
        } else {
            $builder
			->add('ref')
			->add('name')
			->add('image')
			;
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Materials',
			'role' => ['ROLE_USER']
        ));
    }
}
