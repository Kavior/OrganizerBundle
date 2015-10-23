<?php 

namespace Org\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('listName', null, array(
        		'label' => 'Nazwa listy',
        		'attr' => array('class' => 'form-control'),
        		'required' => true,
        ));
        
        $builder->add('customListElements', 'collection', array(
        		'type' => new CustomListElementType(),
        		'label' => false,
        		'allow_add' => true,
        		'by_reference' => false,
        		'allow_delete' => true,
        		'prototype' => true,
        		'attr' => array('class' => 'form-control')
        ));
        
        $builder->add('zapisz', 'submit', array(
        		'attr' => array('class' => 'btn btn-success btn-md')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Org\CoreBundle\Propel\CustomLists',
        ));
    }

    public function getName()
    {
        return 'customList';
    }
}

?>