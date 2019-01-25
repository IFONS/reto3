<?php
require_once "Generic.php";
class Order extends Generic{
    private $commentary, $sdate, $userName, $userSurname, $userTlfo, $userEmail;

    public function __construct($connection){
        parent::__construct($connection);
    }

    public function getCommentary(){
        return $this->commentary;
    }

    public function setCommentary($commentary){
        $this->commentary = $commentary;
    }

    public function getSdate(){
        return $this->sdate;
    }

    public function setSdate($sdate){
        $this->sdate = $sdate;
    }

    public function getUserName(){
        return $this->userName;
    }

    public function setUserName($userName){
        $this->userName = $userName;
    }

    public function getUserSurname(){
        return $this->userSurname;
    }

    public function setUserSurname($userSurname){
        $this->userSurname = $userSurname;
    }

    public function getUserTlfo(){
        return $this->userTlfo;
    }

    public function setUserTlfo($userTlfo){
        $this->userTlfo = $userTlfo;
    }

    public function getUserEmail(){
        return $this->userEmail;
    }

    public function setUserEmail($userEmail){
        $this->userEmail = $userEmail;
    }
    function data(){
        return array(
            "coment"=>$this->commentary,
            "userName"=>$this->userName,
            "userSurname"=>$this->userSurname,
            "userTlfo"=>$this->userTlfo,
            "userEmail"=>$this->userEmail,
            "status"=>"ORDERED"
        );
    }
    public function getAll(){
        $res = parent::getConnection()->prepare(
            "SELECT * FROM clientorder"
        );
        $res->execute();
        return $res->fetchAll();
    }
    public function insertOrder(){
        $res = parent::getConnection()->prepare(
            "INSERT INTO clientOrder (commentary, date, client_name, client_surname, client_number, client_email, status) 
                VALUES (:coment, now(), :userName, :userSurname, :userTlfo, :userEmail, :status)"
        );
        $res->execute($this->data());
        $id = $this->getConnection()->lastInsertId();
        if($id != 0){
            foreach ($_SESSION["cart"] as $e){
                $res = parent::getConnection()->prepare(
                    "INSERT INTO orderproduct (idOrder, idProduct, quantity) 
                VALUES (:idOrder, :idProduct, :quantity)"
                );
                $res->execute(array(
                    "idOrder"=>$id,
                    "idProduct"=>$e["id"],
                    "quantity"=>$e["quantity"]
                ));
            }
        }
        $this->setConnection(null);
        return $id;
    }

    public function updateOrder($id, $commentary, $date, $idUser){
        $res = parent::getConnection()->prepare(
            "UPDATE order SET commentary = :commentary, dateOrder = :dateOrder, idUser = :idUser WHERE id = :id"
        );
        $res->execute(array(
            "id"=>$id,
            "commentary"=>$commentary,
            "dateOrder"=>$date,
            "idUser"=>$idUser
        ));
    }

    public function deleteOrder($id){
        $res = parent::getConnection()->prepare(
            "DELETE FROM order WHERE id = :id"
        );
        $res->execute(array(
            "id"=>$id
        ));
    }

}