<?php 
require 'connect.php';

class Bannawat
{
    function __construct()
    {
        $this->db = database();
    }   

    public function resultuser($id){
        $stmt = $this->db->prepare("SELECT * FROM user_config WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        $result = $stmt->fetch();
        return $result;
    }

    public function authcheck(){
        if (empty($_SESSION['id'])) {
            echo "<script>window.location.href = '/home' </script>";
            exit();
        }
    }
  
    public function fetchsettingwebsitedata($id){
        $stmt = $this->db->prepare("SELECT * FROM setting_website WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        $result = $stmt->fetch();
        return $result;
    }

    function resultcatalog(){
        // Changed status = 0 to status = 1 to match the error context
        $stmt = $this->db->prepare("SELECT * FROM catalog WHERE status = 1 ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function resultid($id = null){
        $stmt = $this->db->prepare("SELECT * FROM user_config WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        $result = $stmt->fetch();
        return $result;
    }
    
    function resulthistory(){
        // Modified to work with the existing catalog table
        if (isset($_SESSION['username'])) {
            $stmt = $this->db->prepare("SELECT * FROM catalog WHERE name_buy = :username_buy ORDER BY date DESC");
            $stmt->execute([':username_buy'=>$_SESSION['username']]);
            $result = $stmt->fetchAll();
            return $result;
        } else {
            return [];
        }
    }

    public function fetchsettingwebsitedata1(){
        $stmt = $this->db->prepare("SELECT * FROM config_web");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
}
?>