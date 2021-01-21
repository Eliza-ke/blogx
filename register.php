<?php
session_start();
require 'config/config.php';
require 'config/common.php';

if($_POST){
  $name =htmlspecialchars($_POST['name']);
	$email =htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

   if(empty($name) || empty($email) || empty($password) || strlen($password)< 4){
  
      if(empty($name)){
        $nameError = 'Name cannot be null';
      }
      if(empty($email)){
        $emailError ='Email cannot be null';
      }
      if(empty($password)){
        $passwordError = 'Password cannot be null';
      }
      if (strlen($password)) {
        $passwordError = 'Password must be more 4 characters';
      }
    }else{
      if(empty($_POST['role'])){
        $role = 0;
      }else {
        $role =1;
      }

      $stmt = $pdo->prepare("SELECT * FROM user WHERE email=:email");

      $stmt->bindValue(':email',$email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user){
        echo "<script>alert('Email duplicated!')</script>";
      }else{

        $pdostatement =$pdo->prepare("INSERT INTO user (name,email,password,role) VALUES (:name ,:email,:password,:role)");
        $result = $pdostatement->execute(
          array(':name' => $name,':email' => $email,':password' => $passwordHash,':role' => $role)
        );
        if ($result) {
          echo "<script>alert('Successfully registered and You can now login !');window.location.href='login.php' </script>";
        }
      }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body class="hold-transition login-page">

<div class="login-box" style="background: lightgreen; padding-top: 5px;">
  <div class="login-logo">
   	<h2 style="color: green"><b>Register</b></h2>
  </div>

  <div class="card" >
    <div class="card-body login-card-body">
      <p class="login-box-msg" >Register New Account</p>

      <form action="register.php" method="post">
        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">

        <p style="color:red;"><?php echo empty($nameError) ? '': '*'.$nameError ?></p>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Name" name="name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        
        <p style="color:red;"><?php echo empty($emailError) ? '': '*'.$emailError ?></p>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <p style="color:red;"><?php echo empty($passwordError) ? '': '*'.$passwordError ?></p>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="container">
            <button type="submit" class="btn btn-success btn-block">Submit</button>
            <a href="login.php" type="button" class="btn btn-defaul btn-block">Login</a>
        </div>

      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
