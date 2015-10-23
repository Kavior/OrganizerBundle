<?php 
namespace Org\CoreBundle\Util;

class Utilities 
{
	public static function getMonthsArray($translator, $firstEmpty = true){
		if($firstEmpty){
			$months = array('' => $translator->trans('-- Wybierz miesiąc --'));
		}else{
			$months = array();
		}
		
		return array_merge($months , array(
				'0' => $translator->trans('Styczeń'),
				'1' => $translator->trans('Luty'),	
				'2' => $translator->trans('Marzec'),
				'3' => $translator->trans('Kwiecień'),
				'4' => $translator->trans('Maj'),
				'5' => $translator->trans('Czerwiec'),
				'6' => $translator->trans('Lipiec'),
				'7' => $translator->trans('Sierpień'),
				'8' => $translator->trans('Wrzesień'),
				'9' => $translator->trans('Październik'),
				'10' => $translator->trans('Listopad'),
				'11' => $translator->trans('Grudzień'),
		));
	}
	
	public static function getDaysOfWeekArray($translator, $firstEmpty = true){
		if($firstEmpty){
			$months = array('' => $translator->trans('-- Wybierz dzień --'));
		}else{
			$months = array();
		}
	
		return array_merge($months , array(
				'0' => $translator->trans('Niedziela'),
				'1' => $translator->trans('Poniedziałek'),
				'2' => $translator->trans('Wtorek'),
				'3' => $translator->trans('Środa'),
				'4' => $translator->trans('Czwartek'),
				'5' => $translator->trans('Piątek'),
				'6' => $translator->trans('Sobota'),
				
		));
	}
	//Information about given section, ex. calendar or cyclical events
	public static function getAppInfo($translator, $controllerName){
		
		switch($controllerName){
			case 'desktop':
				$header = 'Pulpit. Pokazane są tutaj ważniejsze wydarzenia ( z wagą od 7 w górę ) na przestrzeni najbliższych 30 dni.';
				$firstPart = 'Aby zaplanować wydarzenia przejdź do <b>kalendarza</b>.';
				$secondPart = 'Jeśli jakieś zdarzenie pojawia się wieloktornie w twoim harmonogramie, możesz dodać je jako zdarzenie cykliczne - sekcja <b>cykliczne zdarzenia</b>.';
				$thirdPart = 'Dodatkowo do każdego zdarzenia możesz przypiąć listę - może to być na przykład lista zakupów, czy rzeczy do zabrania ze sobą. Ta sama lista może być używana w wielu zdarzeniach.';
				
				$infoText = 
						$translator->trans($header) . '<ul>
						<li>' . $translator->trans($firstPart). '</li>
						<li>' . $translator->trans($secondPart) . '</li>
						<li>'. $translator->trans($thirdPart) .'</li>
						</ul>';
				break;
				
			case 'calendar':
				$infoText = 'Kalendarz. Wybierz datę, aby otworzyć okienko ze zdarzeniami danego dnia. Możesz tam dodawać nowe zdarzenia, lub importować je z dostępnych cyklicznych zdarzeń. Do każdego wydarzenia mogą być dołączone listy ( na przykład lista zakupów ). Mają one również swoją wagę.';
				break;
			
			case 'cyclicalevents':
				$infoText = 'Cykliczne zdarzenia. Ta sekcja służy do dodawania zdarzeń, które występują regularnie w Twoim harmonogramie, dzięki czemu nie ma potrzeby dodawania ich za każdym razem ręcznie. Cykliczne zdarzenia mogą mieć dołączone listy pomocnicze.';
				break;
				
			case 'customlists':
				$infoText = 'Listy pomocnicze. Przydają się, gdy dane zdarzenie jest związane z pewnym zbiorem informacji, na przykład lista osób zaproszonych na przyjęcie czy też plan lekcji. Dodane tutaj listy mogą być później dołączane do zdarzeń.';
				break;
				
			case 'users':
				$infoText = 'Sekcja użytkownika';
				break;
				
			default:
				$infoText = 'Wróć do pulpitu, aby uzyskać więcej informacji';
		}
		
		return $translator->trans($infoText);
		
	}

}

?>