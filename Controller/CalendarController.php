<?php 
namespace Org\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Org\CoreBundle\Propel\Events;
use Org\CoreBundle\Propel\EventsQuery;
use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\CustomListsQuery;
use Org\CoreBundle\Propel\CyclicalEvents;
use Org\CoreBundle\Propel\CyclicalEventsQuery;
use Org\CoreBundle\Propel\EventHasListQuery;
use Org\CoreBundle\Propel\EventHasList;
use JMS\Serializer\SerializerBuilder;
use Org\CoreBundle\Util\EventsUtil;

Class CalendarController extends Controller
{
	public function viewAction($eventId = null)
	{	
		if($eventId != null){  //Enable quick event preview
			if(!$this->validateEvent($eventId)){
				return $this->redirectToRoute('calendar_view');
			}
				
			$event = EventsQuery::create()->findPk($eventId);
			$eventDate = $event->getEventDate();
		}else{
			$eventDate = null;
		}
		
		$request = $this->getRequest();
		$user = $this->getUser();
		$userId = $user->getId();
		
		$translator = $this->get('translator');		
		
		if($request->request->has('listsFromEvent')){ //Load event's lists
			$eventId = $request->request->get('listsFromEvent');

			if(!$this->validateEvent($eventId)){
				return new JsonResponse('{}');
			}
			
			$event = EventsQuery::create()->findPk($eventId);
			$eventsList = $event->getCustomListss();
			//Prevent circular reference
			$serializer = SerializerBuilder::create()->build();
			$jsonContent = $serializer->serialize($eventsList, 'json');
			return new JsonResponse($jsonContent); 
		}
		
		if($request->request->has('chosenDate')){ //Get all events from a chosen day
			$userId = $this->getUser()->getId();
			$chosenDate = $request->request->get('chosenDate');
			$events = EventsQuery::create()
				->filterByIdUser($userId)
				->orderByEventOrder()
				->findByEventDate($chosenDate);
			
			$cyclicalEvents = EventsUtil::getCyclicalEvents($userId,  $chosenDate); //Load cyclical events too

			$arr = array($events, $cyclicalEvents);
			$serializer = SerializerBuilder::create()->build();
			$jsonContent = $serializer->serialize($arr, 'json');
			return new JsonResponse($jsonContent); 
		}
	
		if($request->request->has('eventId')){ //Save event
			//Get all variables
			$eventId = $request->request->get('eventId');
			$chosenDate = $request->request->get('chosenDay');

			$title = $request->request->get('title');
			$content = $request->request->get('content');
			$weight = $request->request->get('weight');
			$order = $request->request->get('order');
				
			if($eventId == null){
				$event = new Events();
			}else{
				if(!$this->validateEvent($eventId)){
					return new JsonResponse('{}');
				}
				$event = EventsQuery::create()->findPk($eventId);
			}
			
			$event->setIdUser($userId);
			$event->setEventName($title)
				->setEventDescription($content)
				->setEventWeight($weight)
				->setEventDate($chosenDate)
				->setEventOrder($order);
				
			$event->save();
			
			return new JsonResponse($event->getIdEvent());
				
		}
		
		if($request->request->has('deletedEventId')){ //Delete event
			$deletedEventId = $request->request->get('deletedEventId');
			
			if(!$this->validateEvent($deletedEventId)){
				return new JsonResponse('{}');
			}
			
			$event = EventsQuery::create()->findPk($deletedEventId);
			$event->delete();
		}
		
		if($request->request->has('addListToEventList')){ //Add list to event
			$addListToEventList = $request->request->get('addListToEventList');
			$addListToEventEvent = $request->request->get('addListToEventEvent');
			
			if(!$this->validateEvent($addListToEventEvent) || !$this->validateList($addListToEventList)){
				return new JsonResponse('{}');
			}

			$eventhasList = new EventHasList();
			$eventhasList->setIdEvent($addListToEventEvent)
				->setIdList($addListToEventList)
				->save();
		}
		
		if($request->request->has('removeListId')){ //Remove list from event
			$removeListId = $request->request->get('removeListId');
			$removeListEvent = $request->request->get('removeListEvent');
			
			if(!$this->validateEvent($removeListEvent) || !$this->validateList($removeListId)){ //Return empty response if there is something wrong with the event
				return new JsonResponse('{}');
			}
			
			//Get a proper row from the junction table
			$eventhasList = EventHasListQuery::create()
				->where('event_has_list.id_event =' . $removeListEvent . ' AND ' . 'event_has_list.id_list = ' . $removeListId);

			$eventhasList->delete();
			
		}
		
		if($request->request->has('getListElements')){ //Get elements of the list assigned to event
			$listId = $request->request->get('getListElements');
			
			if(!$this->validateList($listId)){
				return new JsonResponse('{}');
			}
			
			$listElements = CustomListsQuery::create()->findPk($listId)->getCustomListElements();

			$serializer = SerializerBuilder::create()->build();
			$jsonContent = $serializer->serialize($listElements, 'json');
			return new JsonResponse($jsonContent); 
		}
		
		if($request->request->has('chosenCyclicalEvent')){ //Load cyclical event as a template to new event
			$chosenCyclicalEventId = $request->request->get('chosenCyclicalEvent');
			
			if(!$this->validateCyclicalEvent($chosenCyclicalEventId)){ //Security purpose
				return new JsonResponse('{}');
			}
			
			$chosenDate =  $request->request->get('cyclicalChosenDate');
			$chosenCyclicalEvent = CyclicalEventsQuery::create()->findPk($chosenCyclicalEventId);
			
			//Create and save event
			$newEvent = new Events();
			$newEvent->setEventName($chosenCyclicalEvent->getCyclicalEventName())
				->setEventDescription($chosenCyclicalEvent->getCyclicalEventDescription())
				->setEventWeight($chosenCyclicalEvent->getCyclicalEventWeight())
				->setEventDate($chosenDate)
				->setIdUser($userId);
			$newEvent->save();
			
			//Save event's custom lists
			foreach($chosenCyclicalEvent->getCustomListss() as $list){
				$eventHasList = new EventHasList();
				$eventHasList->setIdEvent($newEvent->getIdEvent())
					->setIdList($list->getIdCustomList());
				$eventHasList->save();
			}
			
			$response = array($chosenCyclicalEvent, $newEvent->getIdEvent());
			
			$serializer = SerializerBuilder::create()->build();
			$jsonContent = $serializer->serialize($response, 'json');
			return new JsonResponse($jsonContent);
		}
		//Perform a list selection
		$chooseText = $translator->trans('-- Wybierz --');
		$listsChoice = CustomListsQuery::getAllListsChoice($userId, $chooseText);
		$cyclicalEventsChoice = CyclicalEventsQuery::getAllEventsChoice($userId, $chooseText);
		
		return $this->render('OrganizerBundle:Calendar:view.html.twig', array(
				'listsChoice' => $listsChoice,
				'cyclicalEventsChoice' => $cyclicalEventsChoice,
				'eventId' => $eventId,
				'eventDate' => $eventDate,
		 ));	
	}
	
	//Render a day card
	public function dayCardAction(){
		return $this->render('OrganizerBundle:Calendar:dayCard.html.twig');	
	}
	
	public function validateEvent($idEvent){ //Protection from accessing another user events
		$userId = $this->getUser()->getId();
		$event = EventsQuery::create()->findPk($idEvent);
		if(!($event instanceof Events) || $event->getIdUser() != $userId){
			return false;
		}
		return true;
	}
	
	public function validateList($idList){ //Protection from accessing another user lists
		$userId = $this->getUser()->getId();
		$list = CustomListsQuery::create()->findPk($idList);

		if(!($list instanceof CustomLists) || $list->getIdUser() != $userId){
			return false;
		}
		return true;
	}
	
	public function validateCyclicalEvent($idCyclicalEvent){ //Protection from accessing another user lists
		$userId = $this->getUser()->getId();
		$cyclicalEvent = CyclicalEventsQuery::create()->findPk($idCyclicalEvent);
		if(!($cyclicalEvent instanceof CyclicalEvents) || $cyclicalEvent->getIdUser() != $userId){
			return false;
		}
		return true;
	}
}