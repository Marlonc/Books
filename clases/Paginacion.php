<?php
class Paginacion{
	private $table;
	private $RecordPages;
	private $Pages = 1;
	private $CurrentPage = 1;
	private $orderby = null;
	private $where = null;
	private $total;

	function __construct($table,$count){
		$this->table = $table;
		$this->RecordPages = $count;
	}
	
	private function reloadPages(){
		$query = mysql_query($this->setQuery());
		$this->total = mysql_num_rows($query);
		if($this->total>$this->RecordPages){
			$t =$this->total/$this->RecordPages;
			$pages = intval(round($t,PHP_ROUND_HALF_EVEN));
			$final = $pages*$this->RecordPages;
			if($final < $this->total)
				$this->Pages = $pages+1;
			else
				$this->Pages = $pages;
		}else{
			$this->Pages = 1;
		}
	}
	public function getCurrentPage(){return $this->CurrentPage;}
	public function getPages(){return $this->Pages;}
	public function getTotal(){return $this->total;}
	public function setOrderBy($orderby){$this->orderby = $orderby;}
	public function setWhere($where){$this->where = $where;}
	public function setQuery(){
		$sql ="SELECT * FROM ".$this->table;
		if($this->where != null){
			$sql = $sql.' WHERE ';
			for ($i=0; $i < count($this->where); $i++) { 
				$and = ($i>0) ? ' AND ' : '' ;
				$sql = $sql.$and.$this->where[$i][0].$this->where[$i][1].$this->where[$i][2];
			}
		}
		if($this->orderby != null){
			$sql = $sql." ORDER BY ".$this->orderby[0]." ".$this->orderby[1];
		}
		return $sql;
	}
	public function getRecords($page){
		$this->reloadPages();
		if($page<=$this->Pages){
			$this->CurrentPage = $page;
			$records = ($page-1) * $this->RecordPages;
			$sql = $this->setQuery();
			$sql = $sql." LIMIT ".$records.",".$this->RecordPages." ";
			$query = mysql_query($sql);
			return $query;
		}
		return false;
	}
}

?>