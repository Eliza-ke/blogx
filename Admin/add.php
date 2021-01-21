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

  $title = htmlspecialchars($_POST['title']);
  $content = htmlspecialchars($_POST['content']);
  $image = $_FILES['image']['name'];

  if(empty($title) || empty($content) || empty($image)){
  
    if(empty($title)){
      $titleError = 'Title cannot be null';
    }
    if(empty($content)){
      $contentError ='Content cannot be null';
    }
      if(empty($image)){
      $imageError = 'Image cannot be null';
    }    
  }else {
    $file ='image/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if($imageType !='png' && $imageType !='jpg' && $imageType !='jpeg'){
      echo "<script>alert ('Image must bepng,jpg,jpeg') </script>";
    }
    else{
      
      move_uploaded_file($_FILES['image']['tmp_name'], $file);

      $pdostatement =$pdo->prepare("INSERT INTO posts (title,content,image,author_id) VALUES (:title ,:content,:image,:author_id)");
      $result = $pdostatement->execute(
        array(
        ':title' => $title,
        ':content' => $content,
        ':image' => $image,
        ':author_id' => $_SESSION['user_id']
        )
      );
      if ($result) {
        echo "<script>alert('Successfully Added!');window.location.href='index.php' </script>";
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
                <h3 class="card-title">Create New Post</h3>
              </div>              
              <!-- /.card-header -->

              <div class="card-body">
              	<form action="add.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
              		<div class="form-group">
              			<label>Title</label>
                    <p style="color:red;"><?php echo empty($titleError) ? '': '*'.$titleError ?></p>
              			<input type="text" name="title" class="form-control">
              		</div>
              		<div class="form-group">
              			<label>Content</label>
                    <p style="color:red;"><?php echo empty($contentError) ? '': '*'.$contentError ?></p>
              			<textarea class="form-control" name="content"></textarea>
              		</div>
              		<div class="form-group">
              			<label>Image</label>
                    <p style="color:red;"><?php echo empty($imageError) ? '': '*'.$imageError ?></p>
              			<input type="file" name="image" class="form-control">
              		</div>
              		<div class="form-group">
              			<input type="submit" class="btn btn-success" value="Add">
              			<a href="index.php" type="button" class="btn btn-primary">Back</a>
              		</div>
              	</form>
              </div>

<?php include ('footer.php') ?>