<?php

namespace Org\CoreBundle\Propel;

use Org\CoreBundle\Propel\om\BaseCustomListsQuery;

class CustomListsQuery extends BaseCustomListsQuery
{
	public static function getAllListsChoice($idUser, $firstOptionText = null){
		$choices = array();
		
		if($firstOptionText != null) $choices[''] = $firstOptionText;
		
		$allLists = self::create()->orderByListName('asc')->findByIdUser($idUser);
		
		foreach($allLists as $list){
			$choices[$list->getIdCustomList()] = $list->getListName();
		}
		
		return $choices;
	}
}
