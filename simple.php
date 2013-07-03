<?php
	
	class excel{
		private $excel=NULL;
		private $writer=NULL;
		private $reader=NULL;
		private $file=NULL;
		
		public function __construct($path='PHPExcel/PHPExcel.php'){
			include_once($path);
			$this->excel = new PHPExcel();
			$this->excel->setActiveSheetIndex(0);
		}
		public function write($local,$text){
			
			$this->excel->getActiveSheet()->setCellValue($local,$text);
			return $this;
		}
		public function save($path){
			$this->writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
			$this->writer->save($path);
			return $this;
		}
		public function load($path){
			$this->reader=PHPExcel_IOFactory::createReader('Excel5')->load($path)->getSheet(0);		
			return $this;
		}
		public function read($local){
			return $this->reader->getCellByColumnAndRow($local)->getValue();
		}
		
	}
	
	
?>