<?php 
namespace Org\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Org\CoreBundle\Form\Type\CustomListType;

use Org\CoreBundle\Propel\CustomLists;
use Org\CoreBundle\Propel\CustomListElement;
use Org\CoreBundle\Propel\CustomListsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomListsController extends Controller{
	//A list of all created custom lists
	public function indexAction()
	{
		$user = $this->getUser();
		$userId = $user->getId();
		//Generate new list with list generator service
		$generatedList = $this->get('list_generator')
			->setUserId($userId)
			->setEntity('Org\CoreBundle\Propel\CustomLists');

		return $this->render('OrganizerBundle:CustomLists:index.html.twig', 
			array(
				'elements' => $generatedList->getElements(),
				'page' => $generatedList->getPage(),
				'totalPagesCount' => $generatedList->getTotalPages(),
				'entity' =>  $generatedList->getEntity(true),
				'filterColumn' => 'ListName'
			)
		);
	}
	
	public function newListAction()
	{
		$request = $this->getRequest();
		$translator = $this->get('translator');
		$title = $translator->trans('Nowa lista');
		
		$user = $this->getUser();
		$userId = $user->getId();
		
		$customList = new CustomLists();
		$form = $this->createForm(new CustomListType(), $customList);

		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$customList->setIdUser($userId);
			$customList->save();
			
			return $this->redirectToList();
		}
		
		return $this->render('OrganizerBundle:CustomLists:form.html.twig', array(
				'form' => $form->createView(),
				'title' => $title
		));
	}
	
	public function editListAction($id){
		$customList = CustomListsQuery::create()->findPk($id);
		$userId = $this->getUser()->getId();
		
		if(!($customList instanceof CustomLists ) || $customList->getIdUser() != $userId){ //Security purpose
			return $this->redirectToList();
		}
		
		$request = $this->getRequest();
		$translator = $this->get('translator');
		$title = $translator->trans('Edytuj listę');
		
		$form = $this->createForm(new CustomListType(), $customList);
		
		$form->handleRequest($request);
		if ($form->isValid()) {
			$customList->save();		
			return $this->redirectToList();
		}
		
		return $this->render('OrganizerBundle:CustomLists:form.html.twig', array(
				'form' => $form->createView(),
				'title' => $title
		));
	}
	
	//Prewiev custom list
	public function viewAction($id){
		$customList = CustomListsQuery::create()->findPk($id);
		$userId = $this->getUser()->getId();
		$request = $this->getRequest();
	
		if(!($customList instanceof CustomLists ) || $customList->getIdUser() !== $userId){
			return $this->redirectToList();
		}
	
		//Delete request
		if($request->request->has('deletedListId')){
			$listId = $request->request->get('deletedListId');
			$list = CustomListsQuery::create()->findPk($listId);
				
			if($list instanceof CustomLists){
				$list->delete();
				return new JsonResponse(true);
			}
		}
	
		return $this->render('OrganizerBundle:CustomLists:view.html.twig', array(
				'list' => $customList,
		));
	}
	
	public function redirectToList(){
		return $this->redirectToRoute('custom_lists');
	}
}
?>