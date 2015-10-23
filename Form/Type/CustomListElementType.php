<?php 

namespace Org\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomListElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('elementName', null, array(
        		'label' => 'Nazwa',
        		'attr' => array('class' => 'form-control')
        ));
        
        $builder->add('elementDescription', 'textarea', array(
        		'label' => 'Opis',
        		'attr' => array('class' => 'form-control'),
        		'required' => false,
        ));
        
        $builder->add('elementOrder', 'hidden', array(
        		
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Org\CoreBundle\Propel\CustomListElement',
        ));
    }

    public function getName()
    {
        return 'customListElement';
    }
}

?>