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
  $title = htmlspecialchars($_POST['title']);
  $content = htmlspecialchars($_POST['content']);
  $image = $_FILES['image']['name'];

  if(empty($title) || empty($content)){
  
    if(empty($title)){
      $titleError = 'Title cannot be null';
    }
    if(empty($content)){
      $contentError ='Content cannot be null';
    }
  }else {

    if($_FILES['image']['name'] !=null){ 

      $file ='image/'.($_FILES['image']['name']);
      $imageType = pathinfo($file,PATHINFO_EXTENSION);

      if($imageType !='png' && $imageType !='jpg' && $imageType !='jpeg'){
        echo "<script>alert('Image must be png,jpg,jpeg')</script>";

      }else{
        $image =$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$file);

        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
        $result = $stmt->execute();
        if($result){
        echo "<script>alert('Successfully Updated');window.location.href='index.php' </script>";
        }
      }  
    }else{  //if image don't update new.
      $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id' ");
      $result = $stmt->execute();
      if($result){
        echo "<script>alert('Successfully Updated');window.location.href='index.php' </script>";
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
               $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']); 
                $stmt->execute();
                $result =$stmt->fetchAll();
              ?>

              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">

                  <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                  <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                  
                  <div class="form-group">
                    <label>Title</label>
                    <p style="color:red;"><?php echo empty($titleError) ? '': '*'.$titleError ?></p>
                    <input type="text" name="title" class="form-control" value="<?php echo escape($result[0]['title']) ?>">
                  </div>

                  <div class="form-group">
                    <label>Content</label>
                     <p style="color:red;"><?php echo empty($contentError) ? '': '*'.$contentError ?></p>
                    <textarea class="form-control" name="content"><?php echo escape($result[0]['content']) ?>   
                    </textarea>
                  </div>

                  <div class="form-group">
                    <label>Image</label>
                    <img src="image/<?php echo $result[0]['image'] ?>" width="150" height="150">
                    <input type="file" name="image" class="form-control">
                  </div>

                  <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Submit">
                    <a href="index.php" type="button" class="btn btn-primary">Back</a>
                  </div>
                </form>
              </div>

<?php include ('footer.php') ?>