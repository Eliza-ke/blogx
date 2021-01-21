<?php 
session_start();
require 'config/config.php';
require 'config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('location: login.php');
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);  #show blog post
$stmt->execute();
$result =$stmt->fetchAll();
//comment

$blogId = $_GET['id'];

$stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = $blogId"); #show comments
$stmtcmt->execute();
$cmResult =$stmtcmt->fetchAll();

$auResult =[];
if($cmResult){
  foreach ($cmResult as $key => $value) {
    $auId = $cmResult[$key]['author_id'];
    $stmtau = $pdo->prepare("SELECT * FROM user WHERE id=$auId"); #show username that user comment
    $stmtau->execute();
    $auResult[] =$stmtau->fetchAll(); 
  }
}

if($_POST){

  $comment =htmlspecialchars($_POST['comment']);
  if(empty($comment)){
      $commentError = 'Comments cannot be null';
  }else{
    $pdostatement =$pdo->prepare("INSERT INTO comments (content,author_id,post_id) VALUES (:content ,:author_id,:post_id)" );
    $result = $pdostatement->execute(
      array(':content' => $comment,':author_id' => $_SESSION['user_id'],':post_id' => $blogId)
    );
    if ($result) {
      header('location: blogdetail.php?id='.$blogId);
    }
  } 
}  
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <div class="content-wrapper" style="margin-left: 0px !important">
    <section class="content-header">
      <div class="container-fluid">
        <a href="index.php" type="button" class="btn btn-success" style="float: right;">Go Back</a>
        <h1 style="text-align: center;"><?php echo escape($result[0]['title']) ?></h1>  
      </div>      
    </section>

      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">           
              <div class="card-body">
                <img class="img-fluid pad" src="admin/image/<?php echo $result[0]['image']?>">
                <p style="padding:4px;"><?php echo escape($result[0]['content']) ?></p>
              </div>
              <!-- /.card-body -->
              
              <div class="card-footer card-comments">
                <div class="card-comment" >
                  <h3>Comments</h3><hr>
                  <div class="comment-text" style="margin-left: 2px !important">
                    <?php foreach ($cmResult as $key => $value) { ?>
                     <span class="username">
                      <b><?php echo escape($auResult[$key][0]['name']) ?></b>
                      <span class="text-muted float-right"><?php echo $value['created_at'] ?></span>
                      </span><!-- /.username -->
                      <?php echo escape($value['content']) ?>
                  <!-- /.comment-text -->
                  </div>
                  <?php } ?>
                    
                <!-- /.card-comment -->
              </div>
              <!-- /.card-footer -->
              <div class="card-footer" style="padding-left: 2px !important" >
                <form action="" method="post">
                  <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                  
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                  <p style="color:red;"><?php echo empty($commentError) ? '': '*'.$commentError ?></p>
                </form>
              </div>
              <!-- /.card-footer -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top" style="margin-bottom: 20px !important">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
 <?php include('admin/footer.php') ?>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
