<?php

if(!class_exists('p404redirect_dropdown')){
class p404redirect_dropdown{

private $name='drop';
private $options='';
private $class='';
private $onchange='';

public function __construct($str,$class='',$onchange='')
	{
	$this->name=$str;
	
	if($class!='')
	$this->class=$class;
	
	if($onchange!='')
	$this->onchange=$onchange;
	
	}
		
public function add($name,$value)
	{
	$this->options=$this->options. "<option value='$value'>$name</option>";
	}	
	
public function dropdown_print()
	{
		if($this->onchange == '')
		echo "<select size='1' name='" . $this->name. "' id='" . $this->name. "' >" . $this->options . "</select>";
			else
		echo "<select size='1' name='" . $this->name. "' id='" . $this->name. "'  onchange='" . $this->onchange . "' >" . $this->options . "</select>";
	}	
		
public function select($str)
	{
		echo "<script>document.getElementById('" . $this->name . "').value='".$str."'</script>";

	}	
	
public function data_bind($tbl,$name="name",$id="id",$where="",$order="",$limit="")
	{
		global $mysql;
		$res=$mysql->sql(" select $id,$name from PREFIX_$tbl $where $order $limit ");
		while($ar=mysqli_fetch_array($res)){
			$this->add($ar[1],$ar[0]);
		}

		
	}		

}}

