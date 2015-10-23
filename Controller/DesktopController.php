<?php 
namespace Org\CoreBundle\Controller;
use Org\CoreBundle\Propel\CyclicalEventsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Org\CoreBundle\Util\EventsUtil;

class DesktopController extends Controller{
	public function indexAction()
	{
		$userId = $this->getUser()->getId();
		//Set the minimal weight from which events are shown as important events on desktop
		$minimalWeight = 7;
		$todaysDate = date('Y-m-d');
		//Date after 7 days
		$todayPlusSeven = date('Y-m-d', strtotime("+7 days"));
		
		//Use EventsUtil class to get events from today, next 7 days and next 30 days
		$todaysEvents = EventsUtil::getAllEventsInfo($userId, $todaysDate, $minimalWeight);
		$nextSevenDaysEvents = EventsUtil::getXNextDaysEvents($userId, $todaysDate, 7, $minimalWeight);
		$nextThirtyDaysEvents = EventsUtil::getXNextDaysEvents($userId, $todayPlusSeven, 23, $minimalWeight);
		
		return $this->render('OrganizerBundle:Desktop:index.html.twig', 
			array(
				'todaysEvents' => $todaysEvents,
				'nextSevenDaysEvents' => $nextSevenDaysEvents,
				'nextThirtyDaysEvents' => $nextThirtyDaysEvents,	
			)
		);
	}
	
}
?>