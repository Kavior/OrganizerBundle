<?php 

namespace Org\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Org\CoreBundle\Util\Utilities;
use Org\CoreBundle\Propel\CyclicalEventHasList;

class CyclicalEventType extends AbstractType
{
	public function __construct($translator){
		$this->translator = $translator;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cyclicalEventName', null, array(
        		'label' => 'Nazwa zdarzenia',
        		'attr' => array('class' => 'form-control'),
        		'required' => true
        ));
        
        $builder->add('cyclicalEventDescription', 'textarea', array(
        		'label' => 'Opis zdarzenia',
        		'attr' => array('class' => 'form-control'),
        		'required' => false
        ));
        
        $builder->add('cyclicalEventWeight', 'hidden', array(
        		'data' => '5'
        ));
        
        $builder->add('cyclicalEventMonth', 'choice', array(
        		'label' => false,//'Miesiąc',
        		'choices' => Utilities::getMonthsArray($this->translator),
        		'attr' => array('class' => 'period chosen form-control', 
        			'disabled' => 'disabled')
        ));
        
        $builder->add('cyclicalEventWeekDay', 'choice', array(
        		'label' => false,//'Dzień tygodnia',
        		'choices' => Utilities::getDaysOfWeekArray($this->translator),
        		'attr' => array('class' => 'period chosen form-control', 
        			'disabled' => 'disabled')
        ));
        
        $builder->add('cyclicalEventDay', null, array(
        		'label' => false,//'Dzień miesiąca',
        		'attr' => array('class' => 'period form-control spinner', 
        			'disabled' => 'true')
        ));
        
        $builder->add('CyclicalEventHasLists', 'collection', array(
        		'type' => new CyclicalEventHasListType(),
        		'options' => array(
        				'idUser' => $options['idUser']
        		),
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
            'data_class' => 'Org\CoreBundle\Propel\CyclicalEvents',
        	'idUser' => null,
        ));
    }

    public function getName()
    {
        return 'cyclicalEvent';
    }
}

?>