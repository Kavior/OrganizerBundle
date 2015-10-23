<?php 
namespace Org\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
    	->add('username', null, array('label' => 'Nazwa użytkownika',
    			'attr' => array('class' => 'form-control'),
    			'error_bubbling' => true
    	))
    	->add('plainPassword', 'repeated', array(
    			'type' => 'password',
    			'first_options' => array('label' => 'Hasło'),
    			'second_options' => array('label' => 'Powtórz hasło'),
    			'invalid_message' => 'fos_user.password.mismatch',
    			'error_bubbling' => true
    			
    	))
    	->add('submit', 'submit', array(
    			'attr' => array('class' => 'btn btn-default')
    	));
    	
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FOS\UserBundle\Propel\User',
        ));
    }

    public function getName()
    {
        return 'registerForm';
    }
}

?>