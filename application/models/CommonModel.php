<?php 
class CommonModel extends CI_MODEL
{
	
	function vehicleProject($data_id,$data_type)
	{
		if($data_type==4)
		{
			$table='project';
			$field='proj_name as mem_name';
			$where['proj_id = ']=$data_id;
		}
		else if($data_type==3)
		{
			$table='mas_vehicle';
			$field='v_num  as mem_name';
			$where['v_id = ']=$data_id;
		}
		else
		{
			return '';
		}

		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($where);
		$qry_res=$this->db->get();

		//return $this->db->last_query();die;
		if($qry_res->num_rows()>0)
			return $qry_res->row()->mem_name;
		else 
			return '';
	}

	function memberName($mem_id)
	{
		$where['c_id = ']=$mem_id;

		$this->db->select("REPLACE(concat_ws(' ',c_firstname,c_middlename,c_lastname),'  ',' ') as mem_name");
		$this->db->from('mas_members');
		$this->db->where($where);
		$qry_res=$this->db->get();

		//return $this->db->last_query();die;
		if($qry_res->num_rows()>0)
			return $qry_res->row()->mem_name;
		else 
			return '';
	}	

	function memberData($mem_id)
	{
		$where['c_id = ']=$mem_id;

		//$this->db->query("select country_name,city_name,state_name,c_address from mas_members",array($mem_id));

		$this->db->select("country_name,city_name,state_name,c_address,c_mob1,c_mob2,c_email");
		$this->db->from('mas_members');
		$this->db->join('mas_city','mas_city.city_id=mas_members.c_city','left');
		$this->db->join('mas_state','mas_state.state_id=mas_members.c_state','left');
		$this->db->join('mas_country','mas_country.id=mas_members.c_country','left');
		$this->db->where($where);
		$qry_res=$this->db->get();
		
		if($qry_res->num_rows()>0)
		{						
			$address=$qry_res->row()->c_address . ' ' . $qry_res->row()->city_name . ' ' . $qry_res->row()->state_name.' '.$qry_res->row()->country_name;

			$contact='';
			if($qry_res->row()->c_mob1!='')
				$contact.=$qry_res->row()->c_mob1;
			if($qry_res->row()->c_mob2!='')
				$contact.=$contact.','.$qry_res->row()->c_mob2;

			$email=$qry_res->row()->c_email;
			return array("address"=>$address,'contact'=>$contact,'email'=>$email);
		}
		else 
			return '';
	}
//----------------- END Of Class ----------------------------
}