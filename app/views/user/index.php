<?php require_once "../app/bootstrap.php";?>
<?php require_once "../app/views/inc/header.php" ;?>

<div class="col my-3">
  <h1><?php echo $data['title']; ?></h1>
  <span><?php echo $data['rights']; ?></span>
</div>
<?php flash('params_success'); ?>

<div class="col mt-5">

  <hr>
  <h3><a href="/users/params/<?php echo $_SESSION['user_id']; ?>">get </a>Your Parametrs</h3>


</div>

<?php require_once "../app/views/inc/footer.php";?>