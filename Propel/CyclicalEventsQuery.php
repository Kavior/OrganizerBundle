<?php

namespace Org\CoreBundle\Propel;

use Org\CoreBundle\Propel\om\BaseCyclicalEventsQuery;
use Symfony\Component\Translation\Translator;

class CyclicalEventsQuery extends BaseCyclicalEventsQuery
{
	public static function getAllEventsChoice($idUser, $firstOptionText = null){

		$choices = array ();
		
		if($firstOptionText != null) $choices[''] = $firstOptionText;
		
		$allEvents = self::create()->orderByCyclicalEventName('asc')->findByIdUser($idUser);
		
		foreach($allEvents as $event){
			$choices[$event->getIdCyclicalEvent()] = $event->getCyclicalEventName();
		}
		
		return $choices;
	}
}
