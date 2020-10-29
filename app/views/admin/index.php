<?php require_once "../app/bootstrap.php";?>
<?php require_once "../app/views/inc/header.php" ;?>
<?php if($_SESSION['status'] != 'Admin') redirect('index');?>

<h1 class="display-4"><?php echo $data['title']; ?></h1>
<p class="lead"><?php echo $data['rights']; ?></p>
<?php flash('register_success'); ?>
<a href="/admins/register" class="btn btn-primary">Add new user</a>

<div class="col mx-auto mt-5">
  <table class="table table-light">
    <thead class="thead-light">
      <tr>
        <th>#</th>
        <th>Email</th>
        <th>Status</th>
        <th>Group</th>
        <th>Access</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($data['users'] as $user): ?>
      <tr>
        <td><?php echo $user->id; ?></td>
        <td><a href="/users/loginFromAdminUser/<?php echo $user->email;?>"><?php echo $user->email; ?></a></td>
        <td><?php echo $user->status; ?></td>
        <td><?php echo $user->user_group; ?></td>
        <td>
          <?php if(!$user->mute && $user->status != 'Admin') :?>
          <!-- update `mute` to TRUE - it means user is muted -->
          <a href="/admins/mute/<?php echo $user->id; ?>" class="btn btn-outline-danger">ban</a>
          <?php elseif($user->mute && $user->status != 'Admin') :?>
          <!-- update `mute` to FALSE - it means user is NOT muted -->
          <a href="/admins/unmute/<?php echo $user->id; ?>" class="btn btn-outline-success">allow</a>
          <?php endif;?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once "../app/views/inc/footer.php";?>