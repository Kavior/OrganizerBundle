<?php 
namespace Org\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;
use Org\CoreBundle\Form\Type\RegisterType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

Class UsersController extends Controller
{
	public function registerAction()
	{
		if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) //Redirect logged users
		{
			return $this->redirect($this->generateUrl('org_desktop'));
		}
		
		$request = $this->getRequest();
		$translator = $this->get('translator');
		$userAlreadyExists = false;

		$userManager = $this->get('fos_user.user_manager');
		//Create user
		$user = $userManager->createUser();
		$form = $this->createForm(new RegisterType(), $user);

		if($request->getMethod() == 'POST')
		{
			$form->handleRequest($request);

			if($form->isValid()){
				$userAlreadyExists = count( UserQuery::create()->findByUsername($user->getUsername()) ) > 0;
				if(!$userAlreadyExists){ 
					$user->setEnabled(1);
					$userManager->updateUser($user);
					$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
					$this->get('security.token_storage')->setToken($token);
					
					return $this->redirectToRoute('org_desktop');
				}
			}
		}
		
		$vars = array(
				'form' => $form->createView()
				);
		
		if($userAlreadyExists)
			$vars ['uniqueUserError'] = $translator->trans('Użytkownik o takiej nazwie już istnieje!');
		
		return $this->render('OrganizerBundle:Users:register.html.twig', $vars);	
	}
	
	public function loginAction()
	{
		 $authenticationUtils = $this->get('security.authentication_utils');

	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();
	
	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();
	
		return $this->render('OrganizerBundle:Users:login.html.twig', array(
				'last_username' => $lastUsername,
				'error'         => $error,
	
		));
	}
	
	//User profile with the basic information
	public function profileAction(){
		$user = $this->getUser();
		$userManager = $this->get('fos_user.user_manager');
		$request = $this->getRequest();
		
		if($request->request->has('newEmailValue')){
			$newEmailValue = $request->request->get('newEmailValue');

			$user->setEmail($newEmailValue);
			$userManager->updateUser($user);
		}
		
		return $this->render('OrganizerBundle:Users:profile.html.twig', array(
				'user' => $user,
		));
	}
	
	public function changePasswordAction(Request $request)
	{
		$user = $this->getUser();
	
		/** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
		$dispatcher = $this->get('event_dispatcher');
	
		$event = new GetResponseUserEvent($user, $request);
		$dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_INITIALIZE, $event);
	
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}
	
		/** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
		$formFactory = $this->get('fos_user.change_password.form.factory');
	
		$form = $formFactory->createForm();
		$form->setData($user);
	
		$form->handleRequest($request);
	
		if ($form->isValid()) {
			/** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
			$userManager = $this->get('fos_user.user_manager');
	
			$event = new FormEvent($form, $request);
			$dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);
	
			$userManager->updateUser($user);
	
			if (null === $response = $event->getResponse()) {
				$url = $this->generateUrl('user_profile');
				$response = new RedirectResponse($url);
			}
	
			$dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
	
			return $response;
		}
	
		return $this->render('OrganizerBundle:Users:changePassword.html.twig', array(
				'form' => $form->createView()
		));
	}
	
	//Change language
	public function languageAction($lang){
		$request = $this->getRequest();
		$request->getSession()->set('_locale', $lang);
		
		return new JsonResponse(true);
	}
}