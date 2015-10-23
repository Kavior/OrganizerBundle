<?php 
namespace Org\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Org\CoreBundle\Form\Type\CyclicalEventType;
use Org\CoreBundle\Propel\CyclicalEvents;
use Org\CoreBundle\Propel\CyclicalEventsQuery;
use Org\CoreBundle\Propel\CustomListsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;

class CyclicalEventsController extends Controller{
	//List of all cyclical events
	public function indexAction()
	{
		$userId = $this->getUser()->getId();
		$generatedList = $this->get('list_generator')
			->setUserId($userId)
			->setEntity('Org\CoreBundle\Propel\CyclicalEvents');

		return $this->render('OrganizerBundle:CyclicalEvents:index.html.twig', 
			array(
				'elements' => $generatedList->getElements(),
				'page' => $generatedList->getPage(),
				'totalPagesCount' => $generatedList->getTotalPages(),
				'entity' => $generatedList->getEntity(true),
				'filterColumn' => 'CyclicalEventName'
			)
		);
	}
	
	 public function newCyclicalEventAction()
	{
		$request = $this->getRequest();
		$translator = $this->get('translator');
		$cyclicalEvent = new CyclicalEvents();
		$userId = $this->getUser()->getId();

		$title = $translator->trans('Dodaj cykliczne zdarzenie');
		//Generate lists choice
		$chooseText = $translator->trans('-- Wybierz --');
		$listsChoice = CustomListsQuery::getAllListsChoice($userId, $chooseText);
		$form = $this->createForm(new CyclicalEventType($translator), $cyclicalEvent, array('idUser' => $userId));

		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$data = $form->getData();
			
			if(!$this->validateLists($data)) //Security issue
				return $this->redirectToRoute('cyclical_events');
			
			$cyclicalEvent->setIdUser($userId);
			$cyclicalEvent->save();
			return $this->redirectToList();
		}
		
		return $this->render('OrganizerBundle:CyclicalEvents:form.html.twig', array(
				'form' => $form->createView(),
				'title' => $title,
				'listsChoice' => $listsChoice,
		));
	}
	
	public function editCyclicalEventAction($id){
		$cyclicalEvent = CyclicalEventsQuery::create()->findPk($id);
		$userId = $this->getUser()->getId();
		
		if(!($cyclicalEvent instanceof CyclicalEvents ) || $cyclicalEvent->getIdUser() !== $userId){
			return $this->redirectToRoute('cyclical_events');
		}
		
		$request = $this->getRequest();
		$translator = $this->get('translator');
		$weight = $cyclicalEvent->getCyclicalEventWeight();
		
		$title = $translator->trans('Edytuj zdarzenie');
		$chooseText = $translator->trans('-- Wybierz --');
		$listsChoice = CustomListsQuery::getAllListsChoice($userId, $chooseText);
		$form = $this->createForm(new CyclicalEventType($translator), $cyclicalEvent);
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$data = $form->getData();
			
			if(!$this->validateLists($data)) 
				return $this->redirectToRoute('cyclical_events');
			
			$cyclicalEvent->save();
			return $this->redirectToList();
		}
		
		return $this->render('OrganizerBundle:CyclicalEvents:form.html.twig', array(
				'form' => $form->createView(),
				'title' => $title,
				'weight' => $weight,
				'listsChoice' => $listsChoice,
		));
	}
	//Preview cyclical event
	public function viewAction($id){
		$cyclicalEvent = CyclicalEventsQuery::create()->findPk($id);
		$userId = $this->getUser()->getId();
		$request = $this->getRequest();
		
		if(!($cyclicalEvent instanceof CyclicalEvents ) || $cyclicalEvent->getIdUser() !== $userId){
			return $this->redirectToRoute('cyclical_events');
		}
		
		if($request->request->has('deletedEventId')){
			$eventId = $request->request->get('deletedEventId');
			
			$event = CyclicalEventsQuery::create()->findPk($eventId);
			
			if($event instanceof CyclicalEvents){
				$event->delete();
				return new JsonResponse(true);
			}
		}
		
		return $this->render('OrganizerBundle:CyclicalEvents:view.html.twig', array(
			'cyclicalEvent' => $cyclicalEvent,
		));
	}
	
	public function redirectToList(){
		return $this->redirectToRoute('cyclical_events');
	}
	
	public function validateLists($data){ //Prevent from using another user lists
		$eventHasList = $data->getCyclicalEventHasLists();
		$userId = $this->getUser()->getId();
		
		foreach($eventHasList as $list){
			if($list->getCustomLists()->getIdUser() != $userId)
				return false;
		}
		
		return true;
	}
}
?>