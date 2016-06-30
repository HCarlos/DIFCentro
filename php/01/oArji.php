<?php
class oArji {
	 
	 private static $instancia;
	 
	 private function __construct(){ 
		
	 }
	 
	public static function getInstance(){
				if (  !self::$instancia instanceof self){
					  self::$instancia = new self;
				}
				return self::$instancia;
	 }
	 
	function FormatCal($cal=0,$con=0,$ina=0,$pivot=0){
		$calx = "";
		$conx = "";
		$inax = "";
		if ($cal>0){
			$calx = round(floatval($cal),0);
		}
		switch (intval($con)) {
			case 10:
				$conx = "E";
				break;
			case 9:
				$conx = "MB";
				break;
			case 8:
				$conx = "B";
				break;
			case 7:
				$conx = "R";
				break;
			case 6:
				$conx = "D";
				break;
			case 5:
				$conx = "NA";
				break;
			default:
				$conx = "";
				break;
		}
		if ($ina>0){
			// $inax = intval($ina);
			$inax = $ina;
		}

		switch($pivot){
			case 3:
				return $calx; //str_pad($calx, 3, " ", STR_PAD_LEFT);	
				break;
			case 2:
				return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).'   ';	
				break;
			default:
				return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).' '.str_pad($inax, 2, " ", STR_PAD_LEFT);
				break;
		}

	}
	
	
}

?>