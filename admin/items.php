<?php

/*  Items => [manage | edit | update | add | Insert | Activate | Delete |]
*/ 

ob_start(); // Output Buffering Start
session_start();
$pageTitle="Items";
if(isset($_SESSION['Username'])){
    include 'init.php';
//condition ? true: false;
$action=isset($_GET['action']) ? $_GET['action'] : 'manage'; 



//if the page is main page
if($action == 'manage'){

    //select all users except admin
    $stmt = $conn->prepare("SELECT 
                                items.* , 
                                categories.Name AS category_name ,
                                users.Username 
                            FROM 
                                items
                            INNER JOIN 
                                categories
                            ON 
                                categories.CatID = items.Cat_ID
                            INNER JOIN 
                                users
                            ON
                                users.UserID = items.Member_ID
                            ORDER BY
                                ItemID DESC");

    //execute statment
    $stmt->execute();
    
    //assign to variable
    $items = $stmt->fetchAll();
    if (! empty($items)){
    ?>
    
    <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-items text-center table table-bordered">
                        <tr>
                           <td>#ID</td>
                           <td>Item</td>  
                           <td>Name</td> 
                           <td>Description</td> 
                           <td>Price</td> 
                           <td>Adding Date</td> 
                           <td>Category</td> 
                           <td>Member</td> 
                           <td>Control</td> 
                        </tr>
    <?php
    foreach($items as $item){
        echo '<div class="table-input">';
                        echo "<tr>";
                        echo  "<td>" .$item['ItemID']. "</td> ";
                        echo  "<td>";
                        if(empty($item['Image'])){
                            echo "<img class='img-responsive' src='uploads/items/default/pill3.png' alt = '' />";
                        }else{
                            echo "<img class='img-responsive' src='uploads/items/" . $item['Image'] . "' alt = '' />";
                        }
                        "</td> ";
                        echo  "<td>" .$item['Name']. "</td> ";
                        echo  "<td>" .$item['Description']. "</td> ";
                        echo  "<td>" .$item['Price']. "</td>"; 
                        echo  "<td>" .$item['AddDate']. "</td>"; 
                        echo  "<td>" .$item['category_name']. "</td>"; 
                        echo  "<td>" .$item['Username']. "</td>"; 
                        echo " <td> " ;
                             echo "  <a href = 'items.php?action=edit&itemid=" . $item['ItemID'] . "'  class ='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                               <a href = 'items.php?action=delete&itemid=" . $item['ItemID'] . "' class ='btn btn-danger confirm'> <i class='fa fa-close'></i>Delete</a>";
                               

                           if($item['Approve'] == 0){
                            echo "<a href = 'items.php?action=approve&itemid=" . $item['ItemID'] . "'  class ='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                            
                           }
                        echo " </td> ";
                      echo  "</tr>";
         echo '</div>';
                  }
                  ?>
                 </table>
                </div>
            
     <a href="items.php?action=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item </a>        
            </div>
    
    <?php
        }else{
            echo '<div class="container">';
                echo '<div class="nice-message"> There\'s No Items To Show</div>';
                echo '<a href="items.php?action=add" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add New Item 
                     </a>';
            echo '</div>';

        }?>
    
<?php    
}
elseif($action == 'add'){
    ?>
<h1 class="text-center"> Add New Item</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=insert" method= "POST" enctype = "multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <div class="myimg">
                        <img 
                            id='previewMyimg'
                            class='img-responsive' 
                            src='uploads/items/default/pill3.png' 
                            alt = '' 
                            />
                        <div class="avatarpic">
                        <input type="file" id="avatar" name="avatar" class="form-control-lg" >
                        </div>
                    </div>
                        
                </div> <!---end of col-md-4  -->


                <div class="col-md-8">

                    <!-- Start Name Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control-lg" required="required" placeholder="">
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control-lg" autocomplete="off" required="required" placeholder="">
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control-lg" autocomplete="off" required="required" placeholder="">
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Made in Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Made In</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control-lg" autocomplete="off" required="required" placeholder="">
                        </div>
                    </div>
                    <!-- End Made in Field -->
                    <!-- Start Status Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control"  name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">good state</option>
                                <option value="3">used</option>
                                <option value="4">very old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Members Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control"  name="member">
                                <option value="0">...</option>
                                <?php
                                $allMembers = getAllFrom("*", "users", "" , "" , "UserID", "" , 50);
                                foreach($allMembers as $user){
                                    echo "<option value='" . $user['UserID'] . "'> " . $user['Username'] . "</option>";
                                }
                                
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start Category Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control"  name="category">
                                <option value="0">...</option>
                                <?php
                                $allCats = getAllFrom("*", "categories", "where Parent = 0" , "" , "CatID", "", 50);
                                foreach($allCats as $cat){
                                    echo "<option value='" . $cat['CatID'] . "'> " . $cat['Name'] . "</option>";
                                    $childCats = getAllFrom("*", "categories", "where Parent = {$cat['CatID']}" , "" , "CatID");
                                    foreach($childCats as $child){
                                        echo "<option value='" . $child['CatID'] . "'> -> " . $child['Name'] . "</option>";
                                    }
                                }
                                
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <!-- End Catgory Field -->

                    <!-- Start Tags Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control-lg" autocomplete="off"  placeholder="Separatee Tags with comma">
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    
                    <!-- Start SaveButton Field -->
                    <div>
                        <div class="offset-sm-2 offset-md-2"> <!-- It works in bootstrap 4, there were some changes in 
                                                                        -- just use it it without the use of col- prefix
                                                                        --  https://stackoverflow.com/questions/38357137/bootstrap-col-md-offset-not-working
                                                                        -->
                                                                        
                            <input type="submit" value="Add Item" name="add" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!-- End SaveButton Field -->
                </div>  <!-- End col-md-8 Field -->
            </div>  <!-- End row Field -->
        </form>
    </div>  <!-- End container Field -->


<?php
}
elseif($action == 'insert'){

    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //get the variables from the form
      
        echo '<h1 class="text-center"> Insert Item</h1>';
        echo ' <div class="container"> ' ;
      
         //Upload Variables
        $avatarName = $_FILES['avatar']['name'];
        $avatarSize = $_FILES['avatar']['size'];
        $avatarTemp = $_FILES['avatar']['tmp_name'];
        $avatarType = $_FILES['avatar']['type'];

        // List of allowed  file type to Upload
        $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
        // Get Avatar Extension
        $avatarExtension = strtolower(end(explode('.', $avatarName)));
        //we used strtolower b/c the allowed extensions are in lower case , it will cause a bug if the uploaded file extension is in upper case

      
        $name         = $_POST['name'];
        $desc         = $_POST['description'];
        $price        = $_POST['price'];
        $country      = $_POST['country'];
        $status       = $_POST['status'];
        $cat          = $_POST['category'];
        $member       = $_POST['member'];
        $tags         = $_POST['tags'];

              
        
        $formErrors = array();
        if(empty($name)){
            $formErrors[] =  'Name can\'t be <strong>Empty</strong>';
        }
        if(empty($desc)){
            $formErrors[] =  'Description can\'t be <strong>More than 20 chartcters</strong>';
        }
        if(empty($price)){
          $formErrors[] =  'Price can\'t be <strong>Empty</strong>';
      }
        if(empty($country)){
            $formErrors[] = 'Country Made can\'t be <strong>Empty</strong>';
        }
        if($status == 0){
            $formErrors[] = 'Status can\'t be <strong>Empty</strong>';
        }
        if($cat == 0){
            $formErrors[] = 'Status can\'t be <strong>Empty</strong>';
        }
        if($member == 0){
            $formErrors[] = 'Status can\'t be <strong>Empty</strong>';
        }
        if (! empty($avatarName) && ! in_array($avatarExtension , $avatarAllowedExtension)){
            $formErrors[] = 'This Extension is Not<strong>Allowed</strong>';
        }
        if (empty($avatarName)){
            $formErrors[] = 'Avatar is <strong>Required</strong>';
        }
        if ($avatarSize > 4194304){
            $formErrors[] = 'Avatar Can\'t Be More Than <strong>4MB</strong>';
        }
        
        
        foreach($formErrors as $err){
            echo '<div class="alert alert-danger">' . $err . '</div>' ;
        }
        
        
        //chec if there is errors
        if(empty($formErrors)){
        //using random number in front of image to guarantee no repition of image
        $avatar = rand(0, 100000) . '_' . $avatarName;

        /***move_uploaded_file(filename, destination);  ***/
        // filename => is the temporary file name saved in the  $avatarTemp variable
        //destination => the folder to store the image 
        //Note: the image will be stored in the destination folder , only its name will be stored in the DataBase
        move_uploaded_file( $avatarTemp, "uploads\items\\" . $avatar);
        
      // check if the user exists in database
      $check = checkItem("Username" , "users" , $user);
                  //insert the database   
                  $stmt = $conn->prepare("INSERT INTO items
                                  (`Name`, `Description`, `Price`, `CountryMade`, `Image`, `AddDate`, `Status`, `Cat_ID`, `Member_ID`, `Tags`)
                                  VALUES(:zname, :zdesc, :zprice, :zcountry, :zimage, now(), :zstatus, :zcat, :zmember, :ztags)
                                  ");
                  $stmt->execute(array(
                      'zname'       => $name,
                      'zdesc'       => $desc,
                      'zprice'      => $price,
                      'zcountry'    => $country,
                      'zimage'      => $avatar,
                      'zstatus'     => $status ,
                      'zcat'        => $cat , 
                      'zmember'     => $member,
                      'ztags'       => $tags
                  ));
      
                  //echo success message
                  $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted </div> ' ;
                  redirectHome($theMsg , 'back');
            
              }// end of empty errors sections
                     
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
    //chec if Get request itemid is Numeric &Get the integer value of it 
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    //select  all data depending on this id
    $stmt=$conn->prepare("SELECT * FROM items WHERE ItemID = ? ");
    $stmt->execute(array($itemid)); // execute the query
    $item = $stmt ->fetch(); //fetch the data
    $count = $stmt->rowCount(); 

    //if there is such id  do the following
    if($count > 0){
    ?>

<h1 class="text-center"> Edit Item</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=update" method= "POST" enctype = "multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <div class="myimg">
                        <?php
                        $imgsource = empty($item['Image'])? 'uploads/items/default/pill3.png' : "uploads/items/" . $item['Image'];
                        ?>
                        <img 
                                id="previewMyimg"
                                class="img-responsive" 
                                src="<?php echo $imgsource ; ?>" 
                                alt = " "
                                />

                        <!-- this will not upload the image if the user doesn't have avatar
                            * This happened when at first adding user avatar image wasnot required hence there is no
                            * image when fetching using $row['Avatar']
                            * OR if you add user directly from the Dtatbase without adding avatar
                            * So in any cases if no image in the database it will cause to not display the image
                            * So there is a variable it will hold the value of the image name the default value if no image
                            * & the user image if there is user image 
                        -->
                            <!-- <img 
                                id="previewMyimg"
                                class="img-responsive rounded-circle" 
                                src="<?php //echo 'uploads/avatar/' . $row['Avatar'] ?>" 
                                alt = " "
                                /> -->
                        <div class="avatarpic">
                        <input type="file" id="avatar" name="avatar" class="form-control-lg" >
                        </div>
                    </div>
                </div> <!---end of col-md-4  -->


                <div class="col-md-8">

                    <input type="hidden" name ="itemid" value="<?php echo $itemid?>">

                    <!-- Start Name Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" 
                                name="name" 
                                class="form-control-lg" 
                                required="required" 
                                placeholder=""
                                value="<?php echo $item['Name']?>" />
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text"
                                name="description" 
                                class="form-control-lg" 
                                autocomplete="off" 
                                required="required" 
                                placeholder=""
                                value="<?php echo $item['Description']?>" />
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" 
                                name="price" 
                                class="form-control-lg" 
                                autocomplete="off" 
                                required="required" 
                                placeholder=""
                                value="<?php echo $item['Price']?>" />
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Made in Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Made In</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text"
                                name="country" 
                                class="form-control-lg" 
                                autocomplete="off" 
                                required="required" 
                                placeholder="" 
                                value="<?php echo $item['CountryMade']?>" />
                        </div>
                    </div>
                    <!-- End Made in Field -->
                    <!-- Start Status Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control"  name="status">
                                <option value="1" <?php if($item['Status' == 1]){ echo 'selected';}?> >New</option>
                                <option value="2" <?php if($item['Status' == 2]){ echo 'selected';}?> >good state</option>
                                <option value="3" <?php if($item['Status' == 3]){ echo 'selected';}?> >used</option>
                                <option value="4" <?php if($item['Status' == 4]){ echo 'selected';}?> >very old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Members Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control"  name="member">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                    echo "<option value='" . $user['UserID'] . "'";
                                    if($item['Member_ID'] == $user['UserID']){ echo 'selected';}
                                    echo ">" . $user['Username'] . "</option>";
                                }
                                
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start Category Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control"  name="category">
                                <option value="0">...</option>
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach($cats as $cat){
                                    echo "<option value='" . $cat['CatID'] . "'";
                                    if($item['Cat_ID'] == $cat['CatID']){ echo 'selected';}
                                    echo ">" . $cat['Name'] . "</option>";
                                }
                                
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <!-- End Catgory Field -->
                    <!-- Start Tags Field -->
                    <div class= "mb-3 row">
                        <label class="col-sm-2 col-md-2 col-form-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input 
                                type="text" 
                                name="tags" 
                                class="form-control-lg" 
                                autocomplete="off"  
                                placeholder="Separatee Tags with comma"
                                value="<?php echo $item['Tags']?>" />
                        </div>
                    </div>
                    <!-- End Tags Field -->
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
                </div> <!-- end of col-md-8-->
            </div> <!-- end of row-->
        </form>



        <?php
        
        //select all users except admin
        $stmt = $conn->prepare("SELECT 
                                comments.*, users.Username AS Member 
                        FROM 
                            comments 
                        INNER JOIN
                            users
                        ON
                            users.UserID = comments.User_ID 
                        WHERE 
                            Item_ID = ? ");

        //execute statment
        $stmt->execute(array($itemid));

        //assign to variable
        $rows = $stmt->fetchAll();

        //if there is comment print it else no print
        if (! empty($rows)){
        ?>

        <h1 class="text-center">Manage [ <?php echo $item['Name']?> ] Comments</h1>
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr> 
                    <td>Comment</td> 
                    <td>User Name</td> 
                    <td>Added Date</td> 
                    <td>Control</td> 
                </tr>
<?php
foreach($rows as $row){
echo '<div class="table-input">';
                echo "<tr>";
                echo  "<td>" . $row['Comment'] . "</td> ";
                echo  "<td>" . $row['Member'] . "</td>"; 
                echo  "<td>" . $row['CommentDate'] . "</td>"; 
                echo  "<td>
                        <a href = 'comments.php?action=edit&commentid=".$row['CommentID']. "'  class ='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                        <a href = 'comments.php?action=delete&commentid=".$row['CommentID']. "' class ='btn btn-danger confirm'> <i class='fa fa-close'></i>Delete</a>";


                        if($row['Status'] == 0){
                        echo "<a
                            href = 'comments.php?action=approve&commentid=" 
                            . $row['CommentID'] . "'  
                            class ='btn btn-info activate'> 
                            <i class='fa fa-check'></i>Approve</a>";
                        
                        }
                    echo " </td> ";
                echo  "</tr>";
    echo '</div>';
            }
            ?>
            </table>
        </div>
        <?php }?>

    </div> <!--- End of container--->

<?php
    }
}
elseif($action == 'update'){
    echo '<h1 class="text-center"> Update Item</h1>';
    echo ' <div class="container"> ' ;
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
  //get the variables from the form


    //Upload Variables
    $avatarName = $_FILES['avatar']['name'];
    $avatarSize = $_FILES['avatar']['size'];
    $avatarTemp = $_FILES['avatar']['tmp_name'];
    $avatarType = $_FILES['avatar']['type'];

    // List of allowed  file type to Upload
    $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

    // Get Avatar Extension
    $avatarExtension = strtolower(end(explode('.', $avatarName)));
    //we used strtolower b/c the allowed extensions are in lower case , it will cause a bug if the uploaded file extension is in upper case


        $id           = $_POST['itemid'];
        $name         = $_POST['name'];
        $desc         = $_POST['description'];
        $price        = $_POST['price'];
        $country      = $_POST['country'];
        $status       = $_POST['status'];
        $cat          = $_POST['category'];
        $member       = $_POST['member'];
        $tags         = $_POST['tags'];


              
        
        $formErrors = array();
        if(empty($name)){
            $formErrors[] =  'Name can\'t be <strong>Empty</strong>';
        }
        if(empty($desc)){
            $formErrors[] =  'Description can\'t be <strong>More than 20 chartcters</strong>';
        }
        if(empty($price)){
          $formErrors[] =  'Price can\'t be <strong>Empty</strong>';
      }
        if(empty($country)){
            $formErrors[] = 'Country Made can\'t be <strong>Empty</strong>';
        }
        if($status == 0){
            $formErrors[] = 'Status can\'t be <strong>Empty</strong>';
        }
        if($cat == 0){
            $formErrors[] = 'Status can\'t be <strong>Empty</strong>';
        }
        if($member == 0){
            $formErrors[] = 'Status can\'t be <strong>Empty</strong>';
        }
        if (! empty($avatarName) && ! in_array($avatarExtension , $avatarAllowedExtension)){
            $formErrors[] = 'This Extension is Not<strong>Allowed</strong>';
        }
        if (empty($avatarName)){
            $formErrors[] = 'Avatar is <strong>Required</strong>';
        }
        if ($avatarSize > 4194304){
            $formErrors[] = 'Avatar Can\'t Be More Than <strong>4MB</strong>';
        }
        
        
        foreach($formErrors as $err){
            echo '<div class="alert alert-danger">' . $err . '</div>' ;
        }
        
        
  
  //chec if there is errors
  if(empty($formErrors)){

    //using random number in front of image to guarantee no repition of image
    $avatar = rand(0, 100000) . '_' . $avatarName;

    /***move_uploaded_file(filename, destination);  ***/
    // filename => is the temporary file name saved in the  $avatarTemp variable
    //destination => the folder to store the image 
    //Note: the image will be stored in the destination folder , only its name will be stored in the DataBase
    move_uploaded_file( $avatarTemp, "uploads\items\\" . $avatar);


  
  //update the database with this info
  
  $stmt = $conn -> prepare("UPDATE
                                items
                            SET
                                `Name` = ? ,
                                `Description` = ? , 
                                `Price` = ? , 
                                `CountryMade` = ?,
                                `Image` = ?,
                                `Status` = ?,
                                `Cat_ID` = ?,
                                `Member_ID` = ?,
                                `tags`   =? 
                            WHERE ItemID = ?");
  $stmt -> execute(array($name, $desc, $price, $country, $avatar, $status, $cat, $member, $tags, $id));
   
  //echo success message
        $theMsg= "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div> ' ;
        redirectHome($theMsg ,'back');
  }//empty errors
  
  }//checing if data is being sent via post
  else{
      
      $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . 'Sorry you can\'t browse this page directly </div> ';
      redirectHome($theMsg);
  }
  
  
  echo ' </div> ';


}

elseif($action == 'delete'){
    echo "<h1 class='text-center'>Delete Item</h1>";
    echo  "<div class='container'>";
 
         $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
     //select  all data depending on this id
       
 
         $check = checkItem("ItemID" , "items" , $itemid);
 
 
     //if there is such id  do the following
         if($check > 0){
         $stmt=$conn->prepare("DELETE  FROM items WHERE ItemID = :zid  LIMIT 1");
         $stmt->bindParam(":zid" , $itemid);
         $stmt->execute();
 
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted </div> ' ;
         redirectHome($theMsg, 'back' );
 
         }
         else{
             $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . 'This id doesn\'t exist </div> ';
             redirectHome($theMsg );
             }
 
   echo '</div>';
    
}
elseif($action == 'approve'){
    echo "<h1 class='text-center'>Approve Item</h1>";
    echo  "<div class='container'>";
 
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
    //select  all data depending on this id
      

        $check = checkItem("ItemID" , "items" , $itemid);

     //if there is such id  do the following
         if($check > 0){
         $stmt = $conn->prepare("UPDATE items SET Approve = 1 WHERE ItemID =?");
         $stmt->execute(array($itemid));
 
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Activated </div> ' ;
         redirectHome($theMsg, 'back' );
 
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