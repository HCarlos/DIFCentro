<?php
class oPHPExcel {
	 
	 private static $instancia;
	 
	 private function __construct(){ 
		
	 }
	 
	public static function getInstance(){
				if (  !self::$instancia instanceof self){
					  self::$instancia = new self;
				}
				return self::$instancia;
	 }
	 
	function cellColor($objPHPExcel, $cells, $color){
		global $objPHPExcel;
		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
		->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
		'startcolor' => array('rgb' => $color)
		));
	}

	
} // Class

?>