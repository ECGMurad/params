<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4"><?php echo $data['title']; ?></h1>
    <p class="lead"><?php echo $data['rights']; ?></p>
    
    <hr class="my-4">


    
    <div class="col-10 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h5 class="card-title">Create An Account</h5>

        <form method="post" action="<?php echo URLROOT;?>/users/register">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : '' ?>" name="email"  value="<?php echo (!empty($data['email'])) ? $data['email'] : '' ?>">
            <span class="text-danger"><?php echo (!empty($data['email_err'])) ? $data['email_err'] : '' ?></span>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : '' ?>" name="username"  value="<?php echo (!empty($data['username'])) ? $data['username'] : '' ?>">
            <span class="text-danger"><?php echo (!empty($data['username_err'])) ? $data['username_err'] : '' ?></span>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : '' ?>" name="password" value="<?php echo (!empty($data['password'])) ? $data['password'] : '' ?>">
            <span class="text-danger"><?php echo (!empty($data['password_err'])) ? $data['password_err'] : '' ?></span>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_group">Group Of Users</label>
              <select name="user_group" class="form-control">
                <option selected value='1'>Users</option>
                <option value='2'>User-1-2</option>
                <option value='3'>User-1-3</option>
                <option value='4'>User-2-3</option>
              </select>
            </div>
            <div class="form-group col-md-6 my-3">
              <input class="btn btn-success btn-block my-3" type="submit" value="Register"></input>
            </div>
          </div>

        </form>
        
      </div>
    </div>

    <hr class="my-4">

  </div>
</div>