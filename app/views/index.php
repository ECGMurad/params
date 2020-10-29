<?php require_once "../app/bootstrap.php";?>
<?php require_once "../app/views/inc/header.php" ;?>

<ul class="nav">
  <?php foreach($data['users'] as $user): ?>
    <li class="nav-item">
      <a class="nav-link" href="/admins/loginFromAdminUser/<?php echo $user->email;?>"><?php echo '| '.$user->email.' |'; ?></a>
    </li>
  <?php endforeach; ?>
</ul>

<div class="col text-center my-5">
  <h1><?php echo $data['title']; ?></h1>
  <span><?php echo $data['rights']; ?></span>
</div>

<?php flash('muted_user'); ?>

<div class="col-8 mx-auto">
  <div class="card card-body bg-light mt-5">

    <form action="<?php echo URLROOT;?>/users/login" method="post">

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="text" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : '' ?>" name="email"  value="<?php echo (!empty($data['email'])) ? $data['email'] : '' ?>">
        <span class="text-danger"><?php echo (!empty($data['email_err'])) ? $data['email_err'] : '' ?></span>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : '' ?>" name="password" value="<?php echo (!empty($data['password'])) ? $data['password'] : '' ?>">
        <span class="text-danger"><?php echo (!empty($data['password_err'])) ? $data['password_err'] : '' ?></span>
      </div>

      <input class="btn btn-success btn-block mt-4" type="submit" value="Login"></input>
      
    </form>
    
  </div>
</div>


<?php require_once "../app/views/inc/footer.php";?>