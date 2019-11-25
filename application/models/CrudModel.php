<?php 
class CrudModel extends CI_Model{
	
	
	public function insert_data($table,$data)
	{
		if($this->db->insert($table,$data))		
		{
			return true;
		}
		else
		{
			return false;
		}	
	}

	public function delete_record($table,$condition)
	{
		if($this->db->delete($table, $condition))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/* 	$table= Table Name , 
		$data = Data Array To Be Updated , 
		$condition= Condition Array  */
	
	public function edit_record($table,$data,$condition)
	{
        $this->db->where($condition);
                
		if($this->db->update($table,$data))		
		{
			return true;
		}
		else
		{
			return false;
		}	
	}


	
	public function select_data($qry,$param)
	{
		$query=$this->db->query($qry,$param);
		return $query->result_array();
	}
	
}
?>