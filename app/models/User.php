<?php

class User {

  private $db;

  public function __construct(){

    $this->db = new Database;

  }

  public function getUsers(){

    $this->db->query("SELECT * FROM users");

    return $this->db->resultSet();

  }

/**
 * get all information about the user and his group
 * 
 * we can add WHERE condition to sql query
 */
  public function getUsersGroups($where = ''){

    $this->db->query("SELECT *,
                      users.id as id,
                      users.status as status,
                      groups.title as user_group
                      FROM users
                      INNER JOIN group_users
                        ON users.id = group_users.user_id
                        INNER JOIN groups
                          ON group_users.group_id = groups.id
                      " . $where);

    if(empty($where)) {

      $results = $this->db->resultSet();

    } else {

      $results = $this->db->single();
    }
    
    return $results;

  }


  public function login($email, $password){

    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    $hashed_password = $row->password;
    
    if(password_verify($password, $hashed_password)){

      return $row;

    } else {

      return false;

    }

  }


  public function findUserByEmail($email){

    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    // Check row
    if($this->db->rowCount() > 0){

      return true;

    } else {

      return false;

    }

  }


  public function getParams($id){

    $this->db->query('SELECT *,
                      parametrs.title as title,
                      parametrs.type as type
                      FROM params_users
                      INNER JOIN users
                        ON params_users.user_id = users.id
                      INNER JOIN parametrs
                        ON params_users.param_id = parametrs.id
                      WHERE params_users.user_id = :id');

    $this->db->bind(':id', $id);

    $results = $this->db->resultSet();

    return $results;

  }


  public function updateParams($data){

    $result = false;

    foreach($data['params'] as $param){


        if($param['param_value'] == '') continue; // it will be empty for inputs that have been disabled

        $this->db->query('UPDATE params_users SET value = :value WHERE param_id = :param_id AND user_id = :user_id');

        $this->db->bind(':value', $param['param_value']);
        $this->db->bind(':param_id', $param['param_id']);
        $this->db->bind(':user_id', $data['user_id']);


        if($this->db->execute()){
          $result = true;
        } else {
          $result = false;
        }
      
    }

    return $result;

  }

  
}