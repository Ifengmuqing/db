<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Daibei_model extends CI_Model
{

    public function __construc()
    {

        parent::__construct();

    }

    function insertCommon($table_name,$data)
    {
        //插入数据，被插入的数据会被自动转换和过滤，例如：
        //$data = array('name' => $name, 'email' => $email, 'url' => $url);
       $sql= $this->db->insert_string($table_name, $data);
       $query=$this->db->query($sql);
       return $query;
    }
    function getCommon($table_name, $array, $offset = 0)
    {
         $query=$this->db->get_where($table_name, $array, $offset);
         return $query->row_array();
    }
}
?>