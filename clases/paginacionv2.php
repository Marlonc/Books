<?php
class Paginacionv2{
	private $table;
	private $RecordPages;
	private $Pages = 1;
	private $CurrentPage = 1;
	private $orderby = null;
	private $where = null;
	private $total;
	private $injoin;
	private $select;

	function __construct($table,$count,$join){
		$this->table = $table;
		$this->RecordPages = $count;
		$this->injoin = $join;
		$this->select = "*";
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
	public function setSelect($select){$this->select = $select;}
	public function setWhere($where){$this->where = $where;}
	public function setQuery(){
		$sql ="SELECT ";
		if($this->select != "*"){
			for ($i=0; $i < count($this->select); $i++) { 
				if($i != 0){
					$sql =$sql.",";	
				}
				$sql =$sql.$this->select[$i];
			}
		}
		$sql= $sql." FROM ";
		for ($i=0; $i < count($this->table); $i++) { 
			if($i != 0){
				$sql =$sql." inner join ".$this->table[$i];	
				if($this->injoin != null){
					$sql =$sql." ON ".$this->injoin[$i-1][0].".".$this->injoin[$i-1][2]."=".$this->injoin[$i-1][1].".".$this->injoin[$i-1][2];
				}
			}else{
				$sql =$sql.$this->table[$i];
			}
		}
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