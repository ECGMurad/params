<?php
class Admin {

  private $db;

  public function __construct(){

    $this->db = new Database;

  }

  /**
   * log in without password from Admins account
   */
  public function loginFromAdmin($email){

    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();
    
    if($row){

      return $row;

    } else {

      return false;

    }
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

  /**
   * mute(banned) users will not be log in
   */
  public function mute($id, $bool){

    $this->db->query('UPDATE users SET mute = :mute WHERE id = :id');

    $this->db->bind(':mute', $bool);
    $this->db->bind(':id', $id);

    if($this->db->execute()){

      return true;

    } else {

      return false;

    }

  }

  public function register($data){

    $this->db->query('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $this->db->bind(':username', $data['username']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', $data['password']);

    if($this->db->execute()){

      return true;

    } else {

      return false;

    }

  }

  /**
   * set users group for user
   * who have been registered
   */
  public function regUserGroup($group){

    $id = $this->db->getLastId();

    $this->setUserParams($id);

    $this->db->query('INSERT INTO group_users (user_id, group_id) VALUES (:user_id, :group_id)');
    $this->db->bind(':user_id', $id);
    $this->db->bind(':group_id', $group);

    if($this->db->execute()){

      return true;

    } else {

      return false;

    }

  }


  /**
   * set parametrs for new user
   * who have been registered
   * values by default
   */
  public function setUserParams($id){
      
    $this->db->query("INSERT INTO params_users (user_id, param_id, value)
                      VALUES (:user_id, 1, 'true'),
                             (:user_id, 2, 'true'),
                             (:user_id, 3, 'false'),
                             (:user_id, 4, 'yes'),
                             (:user_id, 5, 12),
                             (:user_id, 6, 'some text'),");
    $this->db->bind(':user_id', $id);

    if($this->db->execute()){

      return true;

    } else {

      return false;

    }

  }

}