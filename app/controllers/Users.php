<?php

class Users extends Controller {

  public function __construct(){

    $this->userModel = $this->model('User');

  }

/**
 * index page our App
 */
  public function index(){

    // Only for test mode for auto log in for all users by email
    $users = $this->userModel->getUsers();
    
    $data = [
      'title' => 'Main Page',
      'rights' => 'Please Login',
      'users' => $users
    ];

    $this->view('index', $data);

  }


  public function login(){

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [

        'title' => 'Main Page',
        'rights' => 'Please Log in',
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_err' => '',
        'password_err' => ''

      ];

      // Validation
      if(empty($data['email'])) $data['email_err'] = 'Please Enter Email';
      if(empty($data['password'])){
        $data['password_err'] = 'Please Enter Password';
      } elseif (strlen($data['password']) < 6 ) {
        $data['password_err'] = 'Password must be at least 6 characters';
      }

      // Check for user/email
      if(!$this->userModel->findUserByEmail($data['email'])){

        $data['email_err'] = 'No user Found';

      }

      //Make sure errors are empty
      if(empty($data['email_err']) && empty($data['password_err'])){

        //Check and set logged in user
        $loggedInUser = $this->userModel->login($data['email'], $data['password']);

        if($loggedInUser->mute != false){

          flash('muted_user', "You've been Banned!", 'alert alert-danger');
          redirect('index');
          
        } elseif($loggedInUser){

          // Get the Group of User
          $where = "WHERE users.id = ".$loggedInUser->id." LIMIT 1";
          $user_group = $this->userModel->getUsersGroups($where);
          $group = $user_group->user_group;

          // Create Session
          $this->createUserSession($loggedInUser, $group);

          // Check Status
          if($loggedInUser->status == 'Admin'){

            redirect('users/admin');

          } elseif($loggedInUser->status == 'User'){

            redirect('users/user');

          } else {

            die('Something Goes Wrong');

          }

        } else {

          $data['password_err'] = 'Password incorrect';

          $this->view('index', $data);
        }

      } else {

        $this->view('index', $data);

      }
      
    } else {
      
      $data = [

        'title' => 'Main Page',
        'rights' => 'Please Log in',
        'email' => '',
        'password' => '',
        'email_err' => '',
        'password_err' =>''

      ];

      $this->view('index', $data);

    }

  }


  public function createUserSession($user, $group){

    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['username'] = $user->username;
    $_SESSION['status'] = $user->status;
    $_SESSION['user_group'] = $group;    
    
  }


  public function logout(){

    $this->clearSession();

    session_destroy();

    redirect('index');

  }

  public function clearSession(){

    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['username']);
    unset($_SESSION['user_group']);
    if(isset($_SESSION['vip'])) unset($_SESSION['vip']); // will be if admin check user account's

  }


/**
 * admins account page
 */
  public function admin(){

    $users = $this->userModel->getUsersGroups();

    $data = [
      'title' => 'Welcome to Admin Page',
      'rights' => 'You can do anything',
      'users' => $users
    ];
    
    $this->view('admin/index', $data);

  }

/**
 * users account page
 */
  public function user(){

    $data = [
      'title' => 'Welcome to User Page',
      'rights' => 'You can just read information'
    ];

    $this->view('user/index', $data);

  }


  /**
   * get all params
   */
  public function params($id){

    $params = $this->userModel->getParams($id);
  
    $data = [
      'title' => 'Welcome to User Page',
      'rights' => 'You can just read information',
      'params' => $params
    ];
    
    $this->view('user/parametrs', $data);
  
  }


  public function updateParams(){

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [

        'user_id' => $_SESSION['user_id'],
        'params'=> [
          'param1'=>[
            'param_value' => trim($_POST['param1']),
            'param_id' => trim($_POST['param1_id'])
          ],
          'param2'=>[
            'param_value' => trim($_POST['param2']),
            'param_id' => trim($_POST['param2_id'])
          ],
          'param3'=>[
            'param_value' => trim($_POST['param3']),
            'param_id' => trim($_POST['param3_id'])
          ],
          'param4'=>[
            'param_value' => trim($_POST['param4']),
            'param_id' => trim($_POST['param4_id'])
          ],
          'param5'=>[
            'param_value' => trim($_POST['param5']),
            'param_id' => trim($_POST['param5_id'])

          ],
          'param6'=>[
            'param_value' => trim($_POST['param6']),
            'param_id' => trim($_POST['param6_id'])
          ]
        ]
      ];


      //Validation
      foreach($data['params'] as $key => $value){

        //for param5 only because text-param can be anything and other select-param-types of input are limited
        if($key === 'param5'){
          if(!is_numeric($value['param_value'])){
            flash('param_err', 'Param5 must be an integer','alert alert-danger');
            $data['param5_err'] = true;
          }
        }

      }


      //Make sure errors are empty
      if(!isset($data['param5_err'])){

        // Update params
        if($this->userModel->updateParams($data)){

          flash('params_success', 'Parametrs Successfully Updated!');
          $this->user();
          
        } else {

          die('Update Params Failed');
          
        }

      } else {

        $this->params($data['user_id']);
 
      }

    } else {
      
      $data = [

        'title' => 'Welcome to User Page',
        'rights' => 'You can do what you want',

      ];

      $this->view('/user/index', $data);

    }

  }


}