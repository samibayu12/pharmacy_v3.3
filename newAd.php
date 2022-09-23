<?php
session_start();
$pageTitle = 'Create New Item';
 include 'init.php';
//  echo $sessionUser;
 if (isset($_SESSION['user'])){

    if (($_SERVER['REQUEST_METHOD'] == 'POST')){
        $formErrors= array();

        $name           = filter_var($_POST['name']         , FILTER_SANITIZE_STRING);
        $desc           = filter_var($_POST['description']  , FILTER_SANITIZE_STRING);
        $price          = filter_var($_POST['price']        , FILTER_SANITIZE_NUMBER_INT);
        $country        = filter_var($_POST['country']      , FILTER_SANITIZE_STRING);
        $status         = filter_var($_POST['status']       , FILTER_SANITIZE_NUMBER_INT);
        $category       = filter_var($_POST['category']     , FILTER_SANITIZE_NUMBER_INT);
        $tags           = filter_var($_POST['tags']         , FILTER_SANITIZE_STRING);


        if (strlen($name) < 4){
            $formErrors[] = 'Item Name Can\'t Be Less Than 4 Characters ';
        }
        if (strlen($desc) < 10){
            $formErrors[] = 'Item Description Can\'t Be Less Than 10 Characters ';
        }
        if (strlen($country) < 2){
            $formErrors[] = 'Item Country Can\'t Be Less Than 2 Characters ';
        }
        if (empty($price)){
            $formErrors[] = 'Item Price Can\'t Be Empty';
        }
        if (empty($status)){
            $formErrors[] = 'Item Status Can\'t Be Empty';
        }
        if (empty($category)){
            $formErrors[] = 'Item Category Can\'t Be Empty';
        }


        //chec if there is errors
        if(empty($formErrors)){
        
            // check if the user exists in database
            //$check = checkItem("Username" , "users" , $user);
                        //insert the database   
                        $stmt = $conn->prepare("INSERT INTO items
                                        (`Name`, `Description`, `Price`, `AddDate`, `CountryMade`, `Status`, `Cat_ID`, `Member_ID`. `Tags`)
                                        VALUES(:zname, :zdesc, :zprice, now(), :zcountry, :zstatus, :zcat, :zmember, :ztags)
                                        ");
                        $stmt->execute(array(
                            'zname'       => $name,
                            'zdesc'       => $desc,
                            'zprice'      => $price,
                            'zcountry'    => $country,
                            'zstatus'     => $status ,
                            'zcat'        => $category, 
                            'zmember'     => $_SESSION['uid'],
                            'ztags'       => $tags

                        ));
                        if ($stmt){
                            //my edit
                            //echo success message 
                            // $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted </div> ' ;
                            // redirectHome($theMsg , 'back');
                            $successMsg = 'Successfully added';
                        }
                        else {
                            $theMsg = "<div class='alert alert-danger'>" . $stmt->rowCount() . 'Record not Inserted </div> ' ;
                        }
        }

    }

?>

<h1 class="text-center"><?php echo $pageTitle; ?></h1>

<div class="create-ad block">
    <div class="container">
        <div class="card">
            <div class="card-header"><?php echo $pageTitle; ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                            <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method= "POST">
                                <!-- Start Name Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-3 col-form-label">Name</label>
                                    <div class="col-sm-8 col-md-9">
                                        <input 
                                            pattern=".{4,}"
                                            tittle="Name must Be at Least 4 Characters"
                                            type="text" 
                                            name="name" 
                                            class="form-control-lg live" 
                                            required="required" 
                                            placeholder="" 
                                            data-class=".live-name" >
                                    </div>
                                </div>
                                <!-- End Name Field -->
                                <!-- Start Description Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-3 col-form-label">Description</label>
                                    <div class="col-sm-8 col-md-9">
                                        <input
                                            pattern=".{10,}"
                                            tittle="Description must Be at Least 10 Characters" 
                                            type="text" 
                                            name="description" 
                                            class="form-control-lg live" 
                                            autocomplete="off" 
                                            required="required" 
                                            placeholder="" 
                                            data-class=".live-desc"
                                            >
                                    </div>
                                </div>
                                <!-- End Description Field -->
                                <!-- Start Price Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-3 col-form-label">Price</label>
                                    <div class="col-sm-8 col-md-9">
                                        <input 
                                            type="text" 
                                            name="price" 
                                            class="form-control-lg live" 
                                            autocomplete="off" 
                                            required="required" 
                                            placeholder="" 
                                            data-class= ".live-price" 
                                            >
                                    </div>
                                </div>
                                <!-- End Price Field -->
                                <!-- Start Made in Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-3 col-form-label">Made In</label>
                                    <div class="col-sm-8 col-md-9">
                                        <input 
                                            type="text" 
                                            name="country" 
                                            class="form-control-lg" 
                                            autocomplete="off" 
                                            required="required" 
                                            placeholder="">
                                    </div>
                                </div>
                                <!-- End Made in Field -->
                                <!-- Start Status Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-3 col-form-label">Status</label>
                                    <div class="col-sm-8 col-md-6">
                                        <select class="form-control"  name="status" required>
                                            <option value="">...</option>
                                            <option value="1">New</option>
                                            <option value="2">good state</option>
                                            <option value="3">used</option>
                                            <option value="4">very old</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Status Field -->
                                
                                <!-- Start Category Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-3 col-form-label">Category</label>
                                    <div class="col-sm-8 col-md-6">
                                        <select class="form-control"  name="category" required>
                                            <option value="">...</option>
                                            <?php
                                            $cats = getAllFrom('*', 'categories', '', '',  'CatID');
                                            // $stmt = $conn->prepare("SELECT * FROM categories");
                                            // $stmt->execute();
                                            // $cats = $stmt->fetchAll();
                                            foreach($cats as $cat){
                                                echo "<option value='" . $cat['CatID'] . "'> " . $cat['Name'] . "</option>";
                                            }
                                            
                                            ?>
                                            
                                        </select>
                                    </div>
                                </div>
                                <!-- End Category Field -->
                                <!-- Start Tags Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-3 col-md-2 col-form-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="tags" class="form-control-lg" autocomplete="off"  placeholder="Separatee Tags with comma">
                                    </div>
                                </div>
                                <!-- End Tags Field -->
                                <!-- Start SaveButton Field -->
                                <div>
                                    <div class="offset-sm-2 offset-md-3"> <!-- It works in bootstrap 4, there were some changes in 
                                                                                    -- just use it it without the use of col- prefix
                                                                                    --  https://stackoverflow.com/questions/38357137/bootstrap-col-md-offset-not-working
                                                                                    -->
                                                                                    
                                        <input type="submit" value="Add Item" name="add" class="btn btn-primary btn-lg">
                                    </div>
                                </div>
                                <!-- End SaveButton Field -->
                            </form>
                        
                    </div> <!---end of col-md-8  -->

                    
                    <div class="col-md-4">
                        <div class = "thumbnail item-card live-preview">
                            <span class="price-tag">
                                $ 
                                <span class="live-price">0</span>
                            </span>
                            <img class = "img-responsive" src="algorithms.png" alt = "" />
                            <div class = "caption">
                                <h3 class="live-name">Title</h3>
                                <p class="live-desc">Description</p>
                            </div>
                        </div>
                    </div> <!---end of col-md-4  -->

                </div> <!---end of row  -->

                <!-- start looping Errors -->
                <?php
                    if(! empty($formErrors)){
                        foreach ($formErrors as $error){
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    
                    if (isset($successMsg)){
                        echo '<div class="alert alert-success nice-message">' . $successMsg . '</div>';
                    }
                ?>
                <!-- End of Looping Through Errors -->
            </div> <!---end of card body   -->
        </div>  <!---end of card  -->
    </div>  <!---end of container-->
</div>   <!---end of create-ad  div    -->


<?php
 }else{
     header('location: login.php');
     exit();
 }

//include "includes/template/footer.php";
 include $tpl . 'footer.php';
?>