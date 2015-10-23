<?php 
namespace Org\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
class ListGeneratorController extends Controller
{
	//Generate list of elements using list generator
	public function generateAction(Request $request){
		/*
		 * Get parameters from AJAX and pass it to the list generator service. 
		 * It will return a list with a filers, sorting, pagination etc...
		 */
		if($request->isMethod('post')){ 
			if($request->request->has('entity') && $request->request->has('template')){
				$user = $this->getUser();
				$userId = $user->getId();

				$entity = $request->request->get('entity');
				$template = $request->request->get('template');
				//create new list generator
				$generatedList = $this->get('list_generator')
					->setUserId($userId)
					->setEntity($entity);

				$listElementTemplate = $request->request->get('listElementTemplate');
				
				if($request->request->has('page')){ //Go to page
					$page = $request->request->get('page');
					$generatedList->setPage($page);
				}
				
				if($request->request->has('orderBy')){ //Set elements order
					$orderBy = $request->request->get('orderBy');
					$orderType = $request->request->get('orderType');
					
					$generatedList->setOrderBy($orderBy);
					$generatedList->setOrderType($orderType);
				}
				
				if($request->request->has('elementsToRemove')){ //Remove elements
					$elementsToRemove = $request->request->get('elementsToRemove');
					$generatedList->removeElements($elementsToRemove);
				}
				
				if($request->request->has('query')){ //Filter elements using the user's query
					$query = $request->request->get('query');
					$filterColumn = $request->request->get('filterColumn');
					$generatedList->setFilter($filterColumn, $query);
				}
				//Render the list back to the view
				return $this->render($template, array(
						'elements' => $generatedList->getElements(),
						'page' => $generatedList->getPage(),
						'totalPagesCount' => $generatedList->getTotalPages(),
						'entity' => $generatedList->getEntity(),
						'listElementTemplate' => $listElementTemplate
				));
			}
		}
	}
}
?>

