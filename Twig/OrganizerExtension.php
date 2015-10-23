<?php
namespace Org\CoreBundle\Twig;

use Org\CoreBundle\Util\Utilities;
use Symfony\Component\HttpFoundation\Request;

class OrganizerExtension extends \Twig_Extension
{
	private $translator;
	public function __construct($translator) {
		$this->translator = $translator;
	}

	public function setRequest(Request $request = null)
	{
		$this->request = $request;
	}
	
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('shortText', array($this, 'shortText')),
        	new \Twig_SimpleFilter('dayOfAWeek', array($this, 'getWeekDayName')),
        	new \Twig_SimpleFilter('month', array($this, 'getMonthName')),
        );
    }
    
    public function getFunctions()
    {
    	return array(
    			'get_controller_name' => new \Twig_Function_Method($this, 'getControllerName'),
    			'get_section_info' => new \Twig_Function_Method($this, 'getSectionInfo'),
    	);
    }
	//Shorten a text
    public function shortText($string, $maxSigns = 100){
    	if(strlen($string) <= $maxSigns)
    		return $string;
    	else
    		if(!strpos($string, ' '))
				return substr($string, 0 , $maxSigns) . '...';
    		else 
    			return substr($string, 0 , strrpos($string, ' ')) . '...';
    	
    }
    
    public function getWeekDayName($int){
    	$translator =  $this->translator;
    	$daysArray = Utilities::getDaysOfWeekArray($translator);
    	
    	if($int > 6)
    		return $translator->trans('Nieznany');
    	
    	return $translator->trans($daysArray[$int]);
    }
    
    public function getMonthName($int){
    	$translator =  $this->translator;
    	$monthsArray = Utilities::getMonthsArray($translator);
    	 
    	if($int > 11)
    		return $translator->trans('Nieznany');
    	 
    	return $translator->trans($monthsArray[$int]);
    }
    //Current controller name
    public function getControllerName()
    {
    	if(null !== $this->request)
    	{
    		$pattern = "#Controller\\\([a-zA-Z]*)Controller#";
    		$matches = array();
    		preg_match($pattern, $this->request->get('_controller'), $matches);
    
    		return strtolower($matches[1]);
    	}
    
    }
    //Info about current section (calendar, list etc..) 
    public function getSectionInfo($controllerName = null){
    	if($controllerName == null){
    		$controllerName = $this->getControllerName();
    	}
    	
    	$translator =  $this->translator;
    	$sectionInfo = Utilities::getAppInfo($translator, $controllerName);
    	
    	return $sectionInfo;
    }

    public function getName()
    {
        return 'app_extension';
    }
}

?>