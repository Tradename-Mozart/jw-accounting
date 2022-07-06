<?php

class Cd_easyrwd_Model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
		//load our second db and put in $db2
		$this->db_cderwd = $this->load->database('cd_easyrwd', TRUE);
    }

	
	
	public function checkloginStatus()
    {
		if(!isset($_SESSION['user']->is_logged_in))
		{
        redirect('easyrwds');
		}
    }
	
	public function email_check($email)
	{
		
		$query = $this->db_cderwd->get_where('users_public', array('email' => $email)); 
         $data['email'] = $query->result(); 
		 
		 if (isset($data['email'][0]))
		 return $data['email'][0];
		 else return null;
 
	}
	
	public function phonenumber_check($phonenumber)
	{
		
		$this->db_cderwd->select('*');
        $this->db_cderwd->from('users_public');
		$this->db_cderwd->where("(phone = {$phonenumber} OR  phone2 = {$phonenumber}) AND (phone IS NOT NULL) AND (phone2 IS NOT NULL)");
        //$this->db_cderwd->or_where(' =', $phonenumber);
        $query = $this->db_cderwd->get();
         $data['phonenumber'] = $query->result(); 
		 
		 if (isset($data['phonenumber'][0]))
		 return $data['phonenumber'][0];
		 else return null;
		 
 
	}
	
	 public function insert($data,$table) { 
         if ($this->db_cderwd->insert($table, $data)) { 
            return true; 
			//return $this->db_cderwd->insert_id();
         } 
      }
	  
	  public function insert_and_return_key($data,$table) { 
         if ($this->db_cderwd->insert($table, $data)) { 
			return $this->db_cderwd->insert_id();
         } 
      }
	  
	  public function get_data_record($table, $where, $orderbyid=null, $orderbytype=null, $selectCustom = null, $join =NULL)
	{
		// Join will have an associative array, 
		// e.g $join[] = array('table'=>'table', 'filter' => 'a = b AND b = c', 'type' =>'left' )		
		if(!empty($selectCustom))
			$this->db_cderwd->select($selectCustom);
		else 
			$this->db_cderwd->select('*');
			
        $this->db_cderwd->from($table);
		
		if(!empty($join))
		{
			foreach($join as $joinSingle)
			{
				$this->db_cderwd->join($joinSingle['table'], $joinSingle['filter'], $joinSingle['type']);
			}
		}
		
		$this->db_cderwd->where($where);
		
		if(!empty($orderbyid))
		$this->db_cderwd->order_by($orderbyid, (!empty($orderbytype)) ? $orderbytype :'ASC');
		
        $query = $this->db_cderwd->get();
        $data['data'] = $query->result(); 
		 
		 if (isset($data['data'][0]))
		 return $data['data'][0];
		 else return null;
		 
 
	}
	
	
	public function get_data_all($table, $where, $orderbyid=null, $orderbytype=null, $selectCustom = null
								 ,$limit_num_rows = NULL, $limit_start= NULL, $join =NULL)
	{
		if(!empty($selectCustom))
			$this->db_cderwd->select($selectCustom);
		else 
			$this->db_cderwd->select('*');
			
        $this->db_cderwd->from($table);
		
		if(!empty($join))
		{
			foreach($join as $joinSingle)
			{
				$this->db_cderwd->join($joinSingle['table'], $joinSingle['filter'], $joinSingle['type']);
			}
		}
		
		$this->db_cderwd->where($where);
		
		if(!empty($orderbyid))
		$this->db_cderwd->order_by($orderbyid, (!empty($orderbytype)) ? $orderbytype :'ASC');
		
		if(!empty($limit_num_rows))
		  $this->db_cderwd->limit($limit_num_rows,(!empty($limit_start)) ? $limit_start :0);
		
        $query = $this->db_cderwd->get();
        $data['data'] = $query->result(); 
		 
		 if (isset($data['data'][0]))
		 return $data['data'];
		 else return null;
		 
 
}
	
	public function update($data,$where_column,$where_value,$table ) { 
         $this->db_cderwd->set($data); 
         $this->db_cderwd->where($where_column, $where_value); 
         $this->db_cderwd->update($table, $data); 
      } 
	  
	public function updateCustomWhere($data,$where_custom,$table ) { 
         $this->db_cderwd->set($data); 
         $this->db_cderwd->where($where_custom); 
         $this->db_cderwd->update($table, $data); 
      }
	  
	public function customQuery($query)
	{
		$q = $this->db_cderwd->query($query);
	}
	
	public function delete($table, $by_column, $by_column_value) { 
         if ($this->db_cderwd->delete($table, "{$by_column} = ".$by_column_value)) { 
            return true; 
         } 
      } 

}
