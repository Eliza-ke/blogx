<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('location: login.php');
}
if($_SESSION['role'] != 1){
   header('location: login.php');
}
if($_POST){
  
  $id =$_POST['id'];
  $name =htmlspecialchars($_POST['name']);
  $email =htmlspecialchars($_POST['email']);
  $password =htmlspecialchars($_POST['password']);

  if(empty($name) || empty($email)){
  
      if(empty($name)){
        $nameError = 'Name cannot be null';
      }
      if(empty($email)){
        $emailError ='Email cannot be null';
      }
  }elseif (!empty($password) && strlen($password) <4) {
        $passwordError = 'Password must be at least 4 character';
  }else{
    if(empty($_POST['role'])){
      $role = 0;
    }else {
      $role = 1;
  }
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email =:email AND id != :id");
    $stmt->execute( array( ':email' => $email,':id' => $id ));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
      echo "<script>alert('Email duplicate')</script>";
    }
    else{
      if ($password !=null) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET name='$name',email='$email',password='$passwordHash' , role= $role WHERE id='$id'");
      }else{
        $stmt = $pdo->prepare("UPDATE user SET name='$name',email='$email', role= $role WHERE id='$id'");
      }
      $result = $stmt->execute();
      if($result){
        echo "<script>alert('Successfully Updated');window.location.href='user_list.php' </script>";
      }
    } 
  }
}
?>

<?php include ('header.php') ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
     
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <div class="col-md-12">
            <div class="card">
              <div class="card-header" style="background: #eee">
                <h3 class="card-title">Update Post</h3>
              </div>              
              <!-- /.card-header -->

              <?php
               $stmt = $pdo->prepare("SELECT * FROM user WHERE id=".$_GET['id']); 
                $stmt->execute();
                $result =$stmt->fetchAll();
              ?>

              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">

                  <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                  <div class="form-group">
                    <label>Name</label>
                    <p style="color:red;"><?php echo empty($nameError) ? '': '*'.$nameError ?></p>
                    <input type="text" name="name" class="form-control" value="<?php echo escape($result[0]['name']) ?>">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <p style="color:red;"><?php echo empty($emailError) ? '': '*'.$emailError ?></p>
                    <input type="text" name="email" class="form-control" value="<?php echo escape($result[0]['email']) ?>">
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <p style="color:red;"><?php echo empty($passwordError) ? '': '*'.$passwordError ?></p>
                    <input type="text" name="password" class="form-control">
                    <span style="font-size:12px;">*User already has password</span>
                  </div>
                  <div class="form-group">
                    <label>Admin</label>
                    <input type="checkbox" name="role" <?php if( $result[0]['role'] == 1){ echo "checked"; } ?> >                   
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Submit">
                    <a href="user_list.php" type="button" class="btn btn-primary">Back</a>
                  </div>
                </form>
              </div>

<?php include ('footer.php') ?>