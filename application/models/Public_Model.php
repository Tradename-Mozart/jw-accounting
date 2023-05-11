<?php

class Public_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
    }

	
	
	
	
	public function email_check($email)
	{
		
		$query = $this->db->get_where('users_public', array('email' => $email)); 
         $data['email'] = $query->result(); 
		 
		 if (isset($data['email'][0]))
		 return $data['email'][0];
		 else return null;
 
	}
	
	public function phonenumber_check($phonenumber)
	{
		
		$this->db->select('*');
        $this->db->from('users_public');
		$this->db->where("(phone = {$phonenumber} OR  phone2 = {$phonenumber}) AND (phone IS NOT NULL) AND (phone2 IS NOT NULL)");
        //$this->db->or_where(' =', $phonenumber);
        $query = $this->db->get();
         $data['phonenumber'] = $query->result(); 
		 
		 if (isset($data['phonenumber'][0]))
		 return $data['phonenumber'][0];
		 else return null;
		 
 
	}
	
	 public function insert($data,$table) { 
         if ($this->db->insert($table, $data)) { 
            return true; 
			//return $this->db->insert_id();
         } 
      }
	  
	  public function insert_and_return_key($data,$table) { 
         if ($this->db->insert($table, $data)) { 
			return $this->db->insert_id();
         } 
      }
	  
	  public function get_data_record($table, $where, $orderbyid=null, $orderbytype=null, $selectCustom = null, $join =NULL)
	{
		// Join will have an associative array, 
		// e.g $join[] = array('table'=>'table', 'filter' => 'a = b AND b = c', 'type' =>'left' )		
		if(!empty($selectCustom))
			$this->db->select($selectCustom);
		else 
			$this->db->select('*');
			
        $this->db->from($table);
		
		if(!empty($join))
		{
			foreach($join as $joinSingle)
			{
				$this->db->join($joinSingle['table'], $joinSingle['filter'], $joinSingle['type']);
			}
		}
		
		$this->db->where($where);
		
		if(!empty($orderbyid))
		$this->db->order_by($orderbyid, (!empty($orderbytype)) ? $orderbytype :'ASC');
		
        $query = $this->db->get();
        $data['data'] = $query->result(); 
		 
		 if (isset($data['data'][0]))
		 return $data['data'][0];
		 else return null;
		 
 
	}
	
	
	public function get_data_all($table, $where, $orderbyid=null, $orderbytype=null, $selectCustom = null
								 ,$limit_num_rows = NULL, $limit_start= NULL, $join =NULL, $escapeOnJoin = null)
	{
		if(!empty($selectCustom))
			$this->db->select($selectCustom);
		else 
			$this->db->select('*');
			
        $this->db->from($table);
		
		if(!empty($join))
		{
			foreach($join as $joinSingle)
			{
				$this->db->join($joinSingle['table'], $joinSingle['filter'], $joinSingle['type']);
			}
		}
		
		$this->db->where($where);
		
		if(!is_array($orderbyid))
		{
			//Kept this foe legacy purpose
		if(!empty($orderbyid))
		$this->db->order_by($orderbyid, (!empty($orderbytype)) ? $orderbytype :'ASC');
		}
		else if(is_array($orderbyid))
		{
			foreach($orderbyid as $orderSingle)
			{
				$this->db->order_by($orderSingle['field'], $orderSingle['direction'], $escapeOnJoin);
			}
		}
		
		if(!empty($limit_num_rows))
		  $this->db->limit($limit_num_rows,(!empty($limit_start)) ? $limit_start :0);
		
        $query = $this->db->get();
        $data['data'] = $query->result(); 
		 
		 if (isset($data['data'][0]))
		 return $data['data'];
		 else return null;
		 
 
}
	
	public function update($data,$where_column,$where_value,$table ) { 
         $this->db->set($data); 
         $this->db->where($where_column, $where_value); 
         $this->db->update($table, $data); 
      } 
	  
	public function updateCustomWhere($data,$where_custom,$table ) { 
         $this->db->set($data); 
         $this->db->where($where_custom); 
         $this->db->update($table, $data); 
      }
	  
	public function customQuery($query)
	{
		$q = $this->db->query($query);
	}
	
	public function delete($table, $by_column, $by_column_value) { 
         if ($this->db->delete($table, "{$by_column} = ".$by_column_value)) { 
            return true; 
         } 
      } 

	


}
