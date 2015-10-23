<?php

namespace Org\CoreBundle\Propel;

use Org\CoreBundle\Propel\om\BaseCyclicalEventHasList;

class CyclicalEventHasList extends BaseCyclicalEventHasList
{
	public function getListName(){
		return 'aaa';
	}
}
