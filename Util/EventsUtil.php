<?php 
namespace Org\CoreBundle\Util;

use Org\CoreBundle\Propel\CyclicalEventsQuery;
use Org\CoreBundle\Propel\EventsQuery;

class EventsUtil{
	//Get all cyclical events in the chosen date
	public static function getCyclicalEvents($userId,  $date, $minimalWeight = null){
		
		$convertedDate = date(strtotime($date));
		
		$dateDay = date("d", $convertedDate);
		//Get a numeric representation of the day of the week
		$dateWeek = date("w", $convertedDate);
		$dateMonth = intval(date("m", $convertedDate)) - 1; //adjust to app numeration
		
		$query = '( cyclical_events.cyclical_event_week_day = ' . $dateWeek .
		' OR (cyclical_events.cyclical_event_month IS NULL AND cyclical_events.cyclical_event_day = ' . $dateDay . ')' . 
		' OR (cyclical_events.cyclical_event_month =' . $dateMonth . ' AND cyclical_events.cyclical_event_day = ' . $dateDay . ') )';

		if($minimalWeight !== null)
			$query .= ' AND cyclical_events.cyclical_event_weight >= ' . $minimalWeight;
		
		$allCyclicalThisDay = CyclicalEventsQuery::create()
			->where($query)
			->orderByCyclicalEventDay('asc')
			->orderByCyclicalEventWeekDay('asc')
			->filterByIdUser($userId)
			->find();

		return 	$allCyclicalThisDay;
	}
	//Get the normal events in chosen day
	public static function getNormalEvents($userId,  $date, $minimalWeight = null){
		$events = EventsQuery::create()
			->filterByIdUser($userId);
			
		if($minimalWeight !== null){
			$events = $events->where('events.event_weight >= ' . $minimalWeight);
				
		}
		
		$events = $events
			->orderByEventDate('asc')
			->findByEventDate($date);
		
		return $events;
		
	}
	//Get all events from chosen day (both normal and cyclical)
	public static function getAllEventsInfo($userId, $date, $minimalWeight = null){
		$eventsInDay = array();
		
		$cyclicalEvents = self::getCyclicalEvents($userId,  $date, $minimalWeight);
		$normalEvents = self::getNormalEvents($userId, $date, $minimalWeight);
		
		foreach($cyclicalEvents as $cyclical){
			$eventsInDay[] = array(
					'id' => $cyclical->getIdCyclicalEvent(),
					'type' => 'cyclical',
					'name' => $cyclical->getCyclicalEventName(),
					'date' => $date
			);
		}

		foreach($normalEvents as $normal){
			$eventsInDay[] = array(
					'id' => $normal->getIdEvent(),
					'type' => 'normal',
					'name' => $normal->getEventName(),
					'date' => $date
			);
		}
		
		return $eventsInDay;
		
	}
	//Get the events from next X days
	public static function getXNextDaysEvents($userId, $date, $nextDaysCount, $minimalWeight = null){
		$allEventsInDays = array();
		
		for($i = 1; $i < $nextDaysCount; $i++ ){
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
			$events = self::getAllEventsInfo($userId, $date, $minimalWeight);
			
			foreach($events as $ev){
				$allEventsInDays[] = $ev;
			}
		}
		
		return $allEventsInDays;
		
	}
}
?>