<?php
/**
 * get <option> for <select> input depending on value
 */
  function getOption($value){

    if($value == 'true' || $value == 'false'){
      echo ($value == 'true') ? '<option>false</option>' : '<option>true</option>';
    } elseif($value == 'yes' || $value == 'no'){
      echo ($value == 'yes') ? '<option>no</option>' : '<option>yes</option>';
    }

  }

/**
 * get different <inputs> for params depending on their types
 * 
 * there are hidden inputs with param_id and his value for update data
 * `param_id` in `params_users` table
 * 
 */
  function printParam($title, $value, $id, $type, $disabled=''){

    echo '<div class="form-group">';

    if($type == 'string' || $type == 'integer') {

      echo '<div class="col-6">';
      echo '<label for="param">'. $title. '</label>';
      echo '<input name="'.$title.'" value="'.$value.'" '.$disabled.'>';
      echo '<span class="text-danger">'. (!empty($data['param5_err'])) ? $data['param5_err'] : '' .'</span>';
      echo '<input type="hidden" name="'.$title.'_id" value="'.$id.'">';
      echo '</div>';

    } else {

      echo '<div class="col">';
      echo '<label for="param">'. $title. '</label>';
      echo '<select class="custom-select" name="'.$title.'" '. $disabled. '>';
      echo '<option selected>'. $value. '</option>';
      getOption($value);
      echo '</select>';
      echo '<input type="hidden" name="'.$title.'_id" value="'.$id.'">';
      echo '</div>';
    }

    echo '</div>';

  }


?>

<div class="col-8 mx-auto">
  <div class="card card-body bg-light mt-5">
  
  <?php flash('param_err'); ?>

    <form action="<?php echo URLROOT;?>/users/updateParams" method="post">

      <div class="form-row">


      <?php

          if(isset($data['params'])){      

            foreach($data['params'] as $param):

              switch ($_SESSION) {

                // if logged in from admins account
                case ($_SESSION['vip'] == true):
                  printParam($param->title, $param->value, $param->param_id, $param->type );
                break;

                case ($_SESSION['user_group'] == 'user-1-2'):
                  if ($param->title == 'param1' || $param->title == 'param2') {

                    printParam($param->title, $param->value, $param->param_id, $param->type );

                  } else {

                    printParam($param->title, $param->value, $param->param_id, $param->type, 'disabled');

                  }
                break;

                case ($_SESSION['user_group'] == 'user-1-3'):
                  if ($param->title == 'param1' || $param->title == 'param3') {

                    printParam($param->title, $param->value, $param->param_id, $param->type );

                  } else {

                    printParam($param->title, $param->value, $param->param_id, $param->type, 'disabled');
                    
                  }
                break;

                case ($_SESSION['user_group'] == 'user-2-3'):
                  if ($param->title == 'param2' || $param->title == 'param3') {

                    printParam($param->title, $param->value, $param->param_id, $param->type );

                  } else {

                    printParam($param->title, $param->value, $param->param_id, $param->type, 'disabled');
                    
                  }
                break;

                case ($_SESSION['user_group'] == 'users'):
                  printParam($param->title, $param->value, $param->param_id, $param->type, 'disabled');
                break;

              }
              
            endforeach;
          
          }

      ?>
        <!-- Submit button -->
        <div class="col-4 mx-auto">

          <?php
            if(isset($_SESSION['user_group']) && $_SESSION['user_group'] != 'users' || isset($_SESSION['vip'])){ 
            echo '<input class="btn btn-success btn-block mt-4" type="submit" value="Change"></input>';
          }?>
                  <!-- for users from group 'users' there will be no button 'Change' at all -->
                  <!-- an exception will be if you log in from the admin account -->
        </div>
        
      </div>
    </form>
    
  </div>
</div>