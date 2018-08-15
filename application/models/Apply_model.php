<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Apply_model extends CI_Model
{

      public function __construc()
      {

            parent::__construct();

      }
      function addApply($username, $phonenum, $age, $city, $time)
      {
            $this->db->query("insert into stranger (applyName,applyPhoneNum,applyAge,cID,applyTime) VALUES ('$username','$phonenum','$age','$city','$time')");
      }
      function getAll()
      {
            $query = $this->db->query("select stranger.id,stranger.applyname,stranger.applyPhoneNum,stranger.applyTime,stranger.cID,city.cname from stranger left join city on stranger.cid=city.cid  ORDER BY stranger.applyTime DESC");
            return $query->result();
      }
      function delItem($id)
      {
            $query = $this->db->query("delete from stranger where id='{$id}'");
            return $this->db->affected_rows();
      }
}
?>