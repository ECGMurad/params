<?php

class Admins extends Controller {

  private $options;

  public function __construct(){

    $this->userModel = $this->model('Admin');

  }

  /**
   * admins account page
   */
  public function index(){

    $users = $this->userModel->getUsersGroups();

    $data = [
      'title' => 'Welcome to Admin Page',
      'rights' => 'You can do anything',
      'users' => $users
    ];
    
    $this->view('admin/index', $data);

  }


  /**
   * Get Available Options for Admin 
   */
  public function getAdminOptions(Options $obj){

    $this->options = $obj;

  }


  /**
   * registration new users
   */
  public function register(){

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [

        'title' => 'Welcome to Admin Page',
        'rights' => 'You Can Register a New User',
        'email' => trim($_POST['email']),
        'username' => trim($_POST['username']),
        'password' => trim($_POST['password']),
        'email_err' => '',
        'username_err' => '',
        'password_err' => ''

      ];

      //Validation POST data
      if(empty($data['username'])) $data['username_err'] = 'Please Enter Username';

      //email
      if(empty($data['email'])){
        $data['email_err'] = 'Please Enter Email';
      } else {
        //Check for UNIQUE
        if($this->userModel->findUserByEmail($data['email'])) $data['email_err'] = 'This Email is already taken';
      }
      //password
      if(empty($data['password'])){
        $data['password_err'] = 'Please Enter Password';
      } elseif (strlen($data['password']) < 6 ) {
        $data['password_err'] = 'Password must be at least 6 characters';
      }

      //Make sure errors are empty
      if(empty($data['email_err']) && empty($data['username_err']) && empty($data['password_err'])){

        //Hash Password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        //Register User
        if($this->userModel->register($data)){

          // Register group for User
          $user_group = trim($_POST['user_group']);

          if($this->userModel->regUserGroup($user_group)){

            flash('register_success', 'User Registered Successfully!');
            $this->admin();
            
          } else {

            die('User Group Registration Failed');
            
          }

        } else {

          die('The Registration Failed');

        }

      } else {

        $this->view('admin/index', $data);

      } 

    } else {
      
      $data = [

        'title' => 'Welcome to Admin Page',
        'rights' => 'You can do anything',
        'email' => '',
        'username' => '',
        'password' => '',
        'status' => '',
        'email_err' => '',
        'username_err' => '',
        'password_err' =>'',
        'status_err' => ''

      ];

      $this->view('admin/registration', $data);

    }

  }


  /**
   * log in from admin account for any user
   * without a password
   * with VIP status for change params
   */
  public function loginFromAdminUser($email){

    //Check and set logged in user
    $loggedInUser = $this->userModel->loginFromAdmin($email);

    if($loggedInUser){

      // Get the Group of User
      $where = "WHERE users.id = ".$loggedInUser->id." LIMIT 1";
      $user_group = $this->userModel->getUsersGroups($where);
      $group = $user_group->user_group;

      // Create Session
      $this->createUserSession($loggedInUser, $group);
      $_SESSION['vip'] = true;

      // Check Status
      if($loggedInUser->status == 'Admin'){

        redirect('admins/index');

      } elseif($loggedInUser->status == 'User'){

        redirect('users/user');

      } else {

        die('Something Goes Wrong');

      }

    } else {

      die('Method Does Not Work');
    }

  }

  public function createUserSession($user, $group){

    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['username'] = $user->username;
    $_SESSION['status'] = $user->status;
    $_SESSION['user_group'] = $group;    
    
  }


  /**
   * deny access for user
   */
  public function mute($user_id){

    if($this->userModel->mute($user_id, true)){

      flash('user_muted', 'User Successfully Muted');
      redirect('users/admin');

    } else {

      die('Update Mute Request Failed');

    }

  }


  /**
   * allow access for user
   */
  public function unmute($user_id){

    if($this->userModel->mute($user_id, false)){

      flash('user_muted', 'User Successfully Muted');
      redirect('users/admin');

    } else {

      die('Update Mute Request Failed');

    }

  }

}