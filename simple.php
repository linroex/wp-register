<?php
	set_exception_handler('exception_func');
	function exception_func($e){
		die('Some error occur:' . $e->getMessage() . 'on Line ' . $e->getLine());
	}
	
	class excel{
		private $excel = NULL;
		private $writer = NULL;
		private $reader = NULL;
		private $file = NULL;
		private $height = 0;
		
		public function __construct($path='PHPExcel/PHPExcel.php') {
			include_once($path);
			$this->excel = new PHPExcel();
			$this->excel->setActiveSheetIndex(0);
		}
		public function write($local,$text) {
			
			$this->excel->getActiveSheet()->getCell($local)->setValueExplicit($text,PHPExcel_Cell_DataType::TYPE_STRING);
			return $this;
		}
		public function save($path) {
			$this->writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
			$this->writer->save($path);
			return $this;
		}
		public function load($path) {
			$this->reader = PHPExcel_IOFactory::createReader('Excel5')->load($path)->getSheet(0);		
			$this->height = $this->reader->getHighestRow();
			return $this;
		}
		public function read($x,$y) {
			return $this->reader->getCellByColumnAndRow($x,$y)->getValue();
		}
		public function getHeight() {
			return $this->height;
		}
	}
	
	
?>