<?php 

namespace Org\CoreBundle\Util;
/*
 * This class allows us to create a list of elements with pagination, order and filters.
 * You can adjust a number of results per page
 */
class ListGenerator
{
	private $page = 1;
	private $resultsPerPage = 8;
	private $entity = null;
	private $entityManager = null;
	private $number = null;
	private $filter = null;
	private $userId = null;
	private $orderType = null;
	private $orderBy = null;
	
	public function __construct(){
		$this->setPage(1);
		$this->setResultsPerPage(8);
	} 
	
	public function setUserId($userId){
		$this->userId = $userId;
		return $this;
	}
	
	public function getUserId(){
		return $this->userId;
	}
	
	public function setPage($page){
		$this->page = $page;
		$this->filter = null;
		return $this;
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function setOrderType($orderType){
		$this->orderType = $orderType;
		return $this;
	}
	
	public function getOrderType(){
		return $this->orderType;
	}
	
	public function setOrderBy($orderBy){
		$this->orderBy = $orderBy;
		return $this;
	}
	
	public function getOrderBy(){
		return $this->orderBy;
	}
	
	public function getEntity($escapeString = false){
		if($escapeString)
			//return mysql_real_escape_string($this->entity);
			return str_replace('\\', '\\\\', $this->entity);
		return $this->entity;
	}
	
	public function setEntity($entity){
		$this->entity = $entity;
		return $this;
	}
	
	public function getResultsPerPage(){
		return $this->resultsPerPage;
	}
	
	public function setResultsPerPage($number){
		$this->resultsPerPage = $number;
		return $this;
	}
	
	public function getTotalPages(){
		return $this->getElements(true);
	}
	
	public function getName(){
		return 'ListGenerator';
	}
	//Return requested elements
	public function getElements($countPagesOnly = false){
		$getEntity = $this->getEntity() . 'Query';		
		//create Propel objects collection filtered by id of the user
		$entity = $getEntity::create()
			->filterByIdUser($this->getUserId());
		
		//If there are fiters set
		if($this->getFilter() !== null){
			$filter = $this->getFilter();
			$findBy = 'findBy' . $filter['row'];
			$entity->$findBy("%". $filter['value'] ."%");
		}
		
		//If order is set
		if($this->getOrderBy() !== null){
			$orderBy = 'orderBy' . $this->getOrderBy();
			$entity->$orderBy($this->getOrderType());
		}

		$entity = $entity->paginate($this->getPage(), $this->getResultsPerPage());
		
		if($countPagesOnly){
			return $entity->getLastPage() > 0 ? $entity->getLastPage() : 1;
		}

		return $entity;
	}
	//Remove elements from collection
	public function removeElements(array $elements){
		$getEntity = $this->getEntity() . 'Query';		
		$entity = $getEntity::create()
			->findPks($elements)
			->delete();
		
		return $this;
	}
	
	public function setFilter($row, $value){
		$this->filter = array('row' => $row,
				'value' => $value
		);
		return $this;
	}
	
	public function getFilter(){
		return $this->filter;
	}
}
?>