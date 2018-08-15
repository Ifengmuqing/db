<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   class Login_model extends CI_Model{
        function isUser($username,$password){
            $query=$this->db->query("select * from administrator where username='$username' and password='$password'");
            return $query->row_array();
        }
   }
?>