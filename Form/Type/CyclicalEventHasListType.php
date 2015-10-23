<?php 

namespace Org\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Org\CoreBundle\Propel\CustomListsQuery;

class CyclicalEventHasListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idCyclicalEvent', 'hidden');
        
        $builder->add('idList', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Org\CoreBundle\Propel\CyclicalEventHasList',
        	'idUser' => null,
        ));
    }

    public function getName()
    {
        return 'cyclicalEventHasList';
    }
}

?>