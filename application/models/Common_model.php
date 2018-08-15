<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Common_model extends CI_Model
{
    /**
     * 封装查询函数
     */
    public function get_what($table = '', $where = array(), $fields = ' * ')
    {
        if ('' == $table) {
            return false;
        }
        //查询并返回相关结果
        $query = $this->db->select($fields)->where($where)->get($table);
        $res = $query->result_array();
        return $res;
    }
      /**
     * 封装查询函数
     */
    public function get_all_order($table = '', $order_field='',$order_way='DESC',$fields = ' * ')
    {
        if ('' == $table) {
            return false;
        }
        //查询并返回相关结果
        $query = $this->db->select($fields)->order_by($order_field,$order_way)->get($table);
        $res = $query->result_array();
        return $res;
    }
/**
 * 封装单条查询函数
 */
    public function get_row($table = '', $where = array(), $fields = ' * ')
    {
        if ('' == $table) {
            return false;
        }
        //查询并返回相关结果
        $query = $this->db->select($fields)->where($where)->get($table);
        $res = $query->row_array();
        return $res;
    }
/**
 * 封装更新函数
 */
    public function update_what($table = '', $where = array(), $data = array())
    {
        if ('' == $table || true === empty($where) || true === empty($data)) {
            return false;
        }
        //更新相应的字段
        $query = $this->db->update($table, $data, $where);
        return $query;
    }
/**
 * 扩展数据库函数之自增 自减
 * using：
 * $table = 'codeuser';
 * $where = array('id'=>1);
 * $data = array('usestate'=>'usestate+1','imgtype' => 'imgtype-1');
 */
    public function update_count($table = '', $where = array(), $data = array())
    {
        //如果表名为空 或者数据为空则直接 返回false
        if ('' == $table || empty($data)) {
            return false;
        }
        foreach ($data as $key => $val) {
            if (false !== stripos($val, '+') || false !== stripos($val, '-')) {
                $this->db->set($key, $val, false);
            } else {
                $this->db->set($key, $val);
            }
        }
        $res = $this->db->where($where)->update($table);
        return $res;
    }
/**
 * 封装插入函数
 */
    public function insert_what($table = '', $data = array())
    {
        if ('' == $table || true === empty($data)) {
            return false;
        }
        //插入 相关记录
        $query = $this->db->insert($table, $data);
        return $query;
    }
/**
 * 删除记录封装函数
 */
    public function delete_what($table = '', $where = array())
    {
        if (true === empty($where) || '' == $table) {
            return false;
        }
        //删除相关表记录
        $query = $this->db->delete($table, $where);
        return $query;
    }
/**
 * debug 相关函数
 */
    public function debug_what($org_error = '')
    {
        $con = $this->router->fetch_class();
        $func = $this->router->fetch_method();
        if ($org_error) {
            $error .= date("Y-m-d H:i:s", time()) . "\r\n";
            $error .= __FILE__ . "\r\n";
            $error .= $con . " 控制器下的：\r\n";
            $error .= $func . " 方法调试信息如下：\r\n";
            $error .= $org_error;
            file_put_contents("./error_log.txt", $error . "\r\n", FILE_APPEND);
        }
    }
}
