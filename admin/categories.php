<?php

/*  categories => [manage | edit | update | add | Insert | Delete |]
*/ 

ob_start(); // Output Buffering Start
session_start();
$pageTitle="Categories";
if(isset($_SESSION['Username'])){
    include 'init.php';
//condition ? true: false;
$action=isset($_GET['action']) ? $_GET['action'] : 'manage'; 



//if the page is main page
if($action == 'manage'){

$sort = 'ASC';
$sort_array = array('ASC' , 'DESC');
if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
    $sort =  $_GET['sort'];
}
   
$stmt = $conn->prepare("SELECT * FROM categories where Parent = 0 ORDER BY Ordering $sort");

//execute statment
$stmt->execute();

//assign to variable
$cats = $stmt->fetchAll();

?>
    <h1 class="text-center">Manage Categories</h1>
    <div class="container categories">


        <div class="card">
            <div class="card-header">
                <i class="fa fa-edit"></i> Manage Category
                <div class="option float-end">
                    <i class="fa fa-sort"></i>Ordering: [
                    <a class="<?php if($sort =='ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a> |
                    <a class="<?php if($sort =='DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a>]
                    <i class="fa fa-eye"></i>View: [
                    <span class="active" data-view="full">Full</span> |
                    <span data-view="classic">Classic</span> ]     
                </div>
            </div>            <!--end of card heading-->
            <div class="card-body"> 
                    <?php
                    foreach($cats as $cat){
                        echo "<div class='cat'>";
                            echo "<div class='hidden-buttons'>";
                                echo "<a href='categories.php?action=edit&catid=" . $cat['CatID'] . "' class='btn btn-sm btn-primary'> <i class='fa fa-edit'> </i>Edit</a>";
                                echo "<a href='categories.php?action=delete&catid=" . $cat['CatID'] . "' class='btn btn-sm btn-danger confirm'> <i class='fa fa-close'> </i>Delete</a>";
                            echo "</div>";
                            echo "<h3>" . $cat['Name'] . "</h3>";
                            echo "<div class='full-view'>";
                                echo "<p>" ; 
                                    if($cat['Description'] == '')
                                        {echo 'This category has no Description';} 
                                    else{echo $cat['Description'];} 
                                echo "</p>";
                            echo "</div>";

                            // Get Child Categories
                            $childCats = getAllFrom("*", "categories", "where Parent = {$cat['CatID']}", "", "CatID", "ASC" , 7);
                            if (! empty($childCats)){
                                echo "<h4 class= 'child-head'> Sub Categories : </h4>";
                                echo "<ul class='list-unstyled child-cats'>";
                                    foreach ($childCats as $c){
                                    echo  "<li class='child-link'>
                                                <a href='categories.php?action=edit&catid=" . $c['CatID'] . "'>" . $c['Name'] . "</a>
                                                <a href='categories.php?action=delete&catid=" . $c['CatID'] . "' class='show-delete confirm'>Delete</a>
                                            </li>";
                                    }
                                echo "</ul>";
                            }

                        echo "</div>";
                        echo "<hr>";
                    }
                    ?>
            </div>             <!-- end of card body-->
        </div>             <!--end of card-->
        <a class="add-category btn btn-primary" href="categories.php?action=add"><i class= "fa fa-plus"></i>Add New Category</a>
    </div>             <!--end of container-->

<?php

}
elseif($action == 'add'){
  ?>

<h1 class="text-center"> Add New Category</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=insert" method= "POST">
            <!-- Start Name Field -->
            <div class= "mb-3 row">
                <label class="col-sm-2 col-md-2 col-form-label">Name</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name="name" class="form-control-lg" autocomplete="off" required="required" placeholder="">
                </div>
            </div>
            <!-- End Name Field -->

            <!-- Start Description Field -->
            <div class= "mb-3 row">
                <label class="col-sm-2 col-md-2 col-form-label">Description</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name="description" class="password form-control-lg" placeholder="">
                </div>
            </div>
            <!-- End Description Field -->

            <!-- Start Ordering Field -->
            <div class= "mb-3 row">
                <label class="col-sm-2 col-md-2 col-form-label">Ordering</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name="ordering" class="form-control-lg" placeholder="">
                </div>
            </div>
            <!-- End Ordering Field -->

            <!-- Start Category type -->
            <div class= "mb-3 row">
                <label class="col-sm-2 col-md-2 col-form-label">Parent?</label>
                <div class="col-sm-10 col-md-6">
                    <select name="parent" >
                        <option value="0">None</option>
                        <?php
                            $allCats = getAllFrom("*", "categories", "where Parent= 0", "", "CatID", "ASC", 50);
                            foreach($allCats as $cat){
                                echo "<option value = '" . $cat['CatID'] . "'>" . $cat['Name'] . "</option>";
                            }
                        ?>
                    </select>                
                </div>
            </div>
            <!-- End Category type -->

            
            <!-- Start SaveButton Field -->
            <div>
                <div class="offset-sm-2 offset-md-2"> <!-- It works in bootstrap 4, there were some changes in 
                                                        -- just use it it without the use of col- prefix
                                                        --  https://stackoverflow.com/questions/38357137/bootstrap-col-md-offset-not-working
                                                        -->
                                                        
                    <input type="submit" value="Add Category" name="add" class="btn btn-primary btn-lg">
                </div>
            </div>
            <!-- End SaveButton Field -->


  <?php
}
elseif($action == 'insert'){ //insert category page
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //get the variables from the form
      
        echo '<h1 class="text-center">Insert Category</h1>';
        echo ' <div class="container"> ' ;
      
       
        $name         = $_POST['name'];
        $desc         = $_POST['description'];
        $parent       = $_POST['parent'];
        $order        = $_POST['ordering'];
        // $visible      = $_POST['visibilty'];
        // $comment      = $_POST['commenting'];
        // $ads          = $_POST['ads'];

        
      // check if the user exists in database
      $check = checkItem("Name" , "categories" , $name);
      if($check == 1){
          $theMsg = '<div class= "alert alert-danger">the Category with ' .$name . ' name already exists </div>';
          redirectHome($theMsg, 'back');
      }
          else{
                  //insert the database   
                  $stmt = $conn->prepare("INSERT INTO categories
                                  (`Name`, `Description`, `Parent`, `Ordering` )
                                  VALUES(:zname, :zdescription, :zparent, :zorder )
                                  ");
                  $stmt->execute(array(
                      'zname'         => $name,
                      'zdescription'  => $desc,
                      'zparent'       => $parent,
                      'zorder'        => $order,
                    //   'zvisible'      => $visible,
                    //   'zcomment'      => $comment,
                    //   'zads'          => $ads
                  ));
      
                  //echo success message
                  $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted </div> ' ;
                  redirectHome($theMsg , 'back');
              } //end of else section. where this else will be executed if there is no match 
                //in the database of the user to be inserted
              
                     
              echo ' </div> ' ;
      
        }//end of checing if data is being sent via post 
        else{
            echo "<div class = 'container'>";
                $theMsg= '<div class="alert alert-danger">Sorry you can\'t browse this page directly</div>';
                redirectHome($theMsg);
            echo "</div>";
          }
        
        

}

elseif($action == 'edit'){

    
       
        //chec if Get requestuserid is Numeric &Get the integer value of it 
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        //echo $catid;
        //select  all data depending on this id
        $stmt=$conn->prepare("SELECT * FROM categories WHERE CatID = ?");
        $stmt->execute(array($catid)); // execute the query
        $cat = $stmt ->fetch(); //fetch the data
        $count = $stmt->rowCount(); 

        //if there is such id  do the following
        if($count > 0){
        ?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?action=update" method= "POST">
                    <input type="hidden" name="catid" value="<?php echo $catid; ?>" >
                    <!-- Start Name Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control-lg" required="required" placeholder="" value="<?php echo $cat['Name'];?>">
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="password form-control-lg" placeholder="" value="<?php echo $cat['Description'];?>">
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Ordering Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="ordering" class="form-control-lg" placeholder="" value="<?php echo $cat['Ordering'];?>">
                        </div>
                    </div>
                    <!-- End Ordering Field -->

                    <!-- Start Category type -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Parent?</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent" >
                                <option value="0">None</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "where parent= 0", "", "CatID", "ASC", 50);
                                    foreach($allCats as $c){
                                        echo "<option value = '" . $c['CatID'] . "'";
                                        if ($cat['Parent'] == $c['CatID']){ echo 'selected';} 
                                        echo ">" . $c['Name'] . "</option>";
                                    }
                                ?>
                            </select>                
                        </div>
                    </div>
                    <!-- End Category type -->

                    
                    <!-- Start SaveButton Field -->
                    <div>
                        <div class="offset-sm-2 offset-md-2"> <!-- It works in bootstrap 4, there were some changes in 
                                                                -- just use it it without the use of col- prefix
                                                                --  https://stackoverflow.com/questions/38357137/bootstrap-col-md-offset-not-working
                                                                -->
                                                                
                            <input type="submit" value="Save" name="add" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!-- End SaveButton Field -->

<?php }//if we have id of member to be edited
else{
    echo "<div class = 'container'>";
    $theMsg= '<div class="alert alert-danger">no such id</div>';
    redirectHome($theMsg);
    echo "</div>";
    
}


}
elseif($action == 'update'){
    //update page
  echo '<h1 class="text-center"> Update Category</h1>';
  echo ' <div class="container"> ' ;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
//get the variables from the form
$id           = $_POST['catid'];
$name         = $_POST['name'];
$desc         = $_POST['description'];
$order        = $_POST['ordering'];
$parent       = $_POST['parent'];
// $visible      = $_POST['visibilty'];
// $comment      = $_POST['commenting'];
// $ads          = $_POST['ads'];




//update the database with this info

$stmt = $conn -> prepare("UPDATE
                             categories 
                          SET `Name` = ?, 
                            `Description` = ?, 
                            `Ordering` = ?, 
                            Parent = ?
                          WHERE 
                            CatID = ?");
$stmt -> execute(array($name , $desc , $order , $parent, $id ));
 
//echo success message
      $theMsg= "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div> ' ;
      redirectHome($theMsg ,'back');

}//checing if data is being sent via post
else{
    
    $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . 'Sorry you can\'t browse this page directly </div> ';
    redirectHome($theMsg);
}


echo ' </div> ';

}

elseif($action == 'delete'){
   //delete member  page
   echo "<h1 class='text-center'>Delete Category</h1>";
   echo  "<div class='container'>";

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $check = checkItem("CatID" , "categories" , $catid);


    //if there is such id  do the following
        if($check > 0){
        $stmt=$conn->prepare("DELETE  FROM categories WHERE CatID = :zid ");
        $stmt->bindParam(":zid" , $catid);
        $stmt->execute();

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted </div> ' ;
        redirectHome($theMsg , 'back' );

        }
        else{
            $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . 'This id doesn\'t exist </div> ';
            redirectHome($theMsg );
            }

  echo '</div>';

}


include $tpl . 'footer.php';

}//end of session


else{
    header('Location: index.php'); //redirect index page to login 
    exit();
}
ob_end_flush(); // Release the output
?>