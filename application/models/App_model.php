<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   date_default_timezone_set('PRC');//其中PRC为“中华人民共和国”
   class App_model extends CI_Model{
        
        public function __construc() {
      
        parent::__construct();
       
        }
        public function getUser($name){
        	$this->db->select('name,password');
        	$query=$this->db->get_where('weixin',array('name'=>$name));
        	return $query->result();
        }
		public function setOpenId($openid,$name){
			$data=array('openid'=>$openid);
            $this->db->where('name',$name);
            $this->db->update('weixin',$data);
            if($this->db->affected_rows()>0){
                return true;
            }
            else{
                return false;
            }
		}   
      // public function getMoney($personal,$total){
      //      return $this->db->get_where('weixin',array('personal'=>$personal,'total'=>$tatal))->result();
      //  }
       public function getUserByOpenId($openid){
           return $this->db->get_where('weixin',array('openid'=>$openid))->result();
       }
        public function getID_number($ID_number){
           return $this->db->get_where('weixin',array('ID_number'=>$ID_number))->result();
       }
       public function countID_number($ID_number){
          $this->db->select('*');
          $this->db->from('staff');
          $this->db->where('ID_number',$ID_number);
          return  $this->db->count_all_results();  
       }
     
       public function getprojectname($ID_number){
           $this->db->select('*');
           $this->db->from('project');
           $this->db->where("staff.ID_number=$ID_number");
           $this->db->join('staff',"staff.projectid= project.id");
           $query=$this->db->get();
           return $query->result_array();
       }

       public function getweixin($staffid){
           $this->db->select('*');
           $this->db->from('weixin');
           $this->db->where("weixin.staffid=$staffid");
           $this->db->join('staff',"weixin.staffid = staff.id");
           $this->db->join('project',"staff.projectid = project.id");
           $query=$this->db->get();
           return $query->result_array();
       }
         public function setCertificate($data){
           $this->db->insert('certificate',$data);
       }
       public function getCertificate($data){
           $query=$this->db->get_where('certificate',$data);
           return $query->result();
       }
       public function getCertInfo($CNO){
           $this->db->select('*');
           $this->db->from('certificate');
           $this->db->join('weixin',"certificate.name=weixin.name and certificate.CNO=$CNO");
           $query=$this->db->get();
           return $query->result();
       }
       public function getCertList($openid){
           $this->db->select('*');
           $this->db->from('weixin');
           $this->db->join('certificate',"weixin.name=certificate.name and weixin.openid='$openid'");
           $query=$this->db->get();
           return $query->result();
       }
       public function getMoney($openid){
           $this->db->select('personal,total');
           return $this->db->get_where('weixin',array('openid'=>$openid))->result();
         
       }
       public function wxID_number($openid){
           $this->db->select('ID_number');
           return $this->db->get_where('weixin',array('openid'=>$openid))->result();
         
       }
       public function setOpenId1($openid,$ID_number){
            $data=array('openid'=>$openid);
            $this->db->where('ID_number',$ID_number);
            $this->db->update('weixin',$data);
            if($this->db->affected_rows()>0){
                return true;
            }
            else{
                return false;
            }
    } 
       
	}
?>
