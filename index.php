<?php 

session_start();
require 'config/config.php';
require 'config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('location: login.php');
}

if(!empty($_GET['pageno'])) {
    $pageno =$_GET['pageno'];
}else{
    $pageno =1;
}
  $numOfrecs = 6;
  $offset =($pageno -1) * $numOfrecs;

  if(empty($_POST['search'])){

    $stmt =$pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
    $stmt->execute();
    $rawresult = $stmt->fetchAll();
    $total_pages = ceil(count($rawresult) / $numOfrecs);
   
    $stmt =$pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  } else{

    $searchKey = $_POST['search'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
    $stmt->execute();
    $rawresult = $stmt->fetchAll();
    $total_pages = ceil(count($rawresult) / $numOfrecs);
   
    $stmt =$pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
    $stmt->execute();
    $result = $stmt->fetchAll();

    } 
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog</title>
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
    <h3 style="text-align: center;">Blog Site</h3></section>
    <nav style="float: right;margin-bottom: 19px !important" >
    
      <form class="form-inline ml-3" method="post" action="index.php">
        <div class="input-group input-group-sm">
          <input name="search" class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
             <i class="fas fa-search"></i> 
            </button>
          </div>
        </div>
      </form>
    </nav>

    <section class="content" style="clear: both !important">
        <div class="row">
          <?php 
              if($result){
              foreach($result as $value){  
          ?>
          <div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <h4 style="text-align: center;"><?php echo escape($value['title']) ?></h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="height:420px !important">
                <a href="blogdetail.php?id=<?php echo $value['id'];?>">
                <img class="img-fluid pad" src="admin/image/<?php echo $value['image'] ?>" style="width:340px;height:340px !important">
                <p style="padding:4px;"><?php echo escape(substr($value['content'],0,50))?></p>
                </a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        <?php }
            }
        ?>
        
        </div>
        <!-- /.row -->
      </div>
      <ul class="pagination justify-content-end" style="margin:10px">
          <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>

          <li class="page-item <?php if($pageno <= 1) {echo 'disabled';} ?>">
          <a class="page-link" href="<?php if ($pageno <= 1) { echo '#';} else{echo "?pageno=".($pageno - 1);} ?>">Previous</a>
          </li>

          <li class="page-item"><a class="page-link" href="#"><?php echo $pageno ?></a></li>

          <li class="page-item <?php if($pageno >= $total_pages) {echo 'disabled';} ?>">
          <a class="page-link" href="<?php if ($pageno >= $total_pages) { echo '#';} else{echo "?pageno=".($pageno + 1);} ?>">Next</a>
          </li>

          <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
      </ul>
    </section>
    <!-- /.content -->
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
