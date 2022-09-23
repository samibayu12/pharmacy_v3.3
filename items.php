<?php
ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'Items';
include 'init.php';

//chec if Get request itemid is Numeric &Get the integer value of it 
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

//select  all data depending on this id
$stmt=$conn->prepare("SELECT 
                        items.*,
                        categories.Name AS category_name,
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
                      WHERE 
                        ItemID = ?
                      AND
                        Approve = 1 ");
$stmt->execute(array($itemid)); // execute the query
$count = $stmt->rowCount();
if ($count > 0){
$item = $stmt ->fetch(); //fetch the data 
$imgsource = empty($item['Image'])? 'admin/uploads/items/default/pill3.png' : "admin/uploads/items/" . $item['Image'];
?>

<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img class="img-responsive img-thumbnail center-block" src="<?php echo $imgsource;?>" alt="" >
        </div>
        <div class="col-md-9 item-info">
            <!-- <h2><?//php echo $item['Name'] ?></h2> -->
            <p><?php echo $item['Description'] ?></p>
            <ul class="list-unstayled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span><?php echo $item['AddDate'] ?></span>
                </li>
                <li>
                    <i class="fa fa-money fa-fw"></i>
                    <span>Price </span> : $ <?php echo  $item['Price'] ?>
                </li>
                <li>
                    <i class="fa fa-building fa-fw"></i>
                    <span>Made In</span> : <?php echo  $item['CountryMade'] ?>
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"> <?php echo $item['category_name'] ?></a>
                </li>
                <li>
                    <i class="fa fa-user fa-fw"></i>
                    <span> Added By</span> : <a href="#"> <?php echo $item['Username'] ?></a>
                </li>
                <li class="tags-items">
                    <i class="fa fa-user fa-fw"></i>
                    <span> Tags</span> :
                    <?php
                        $allTags = explode("," , $item['Tags']);
                        foreach($allTags as $tag){
                            $tag = str_replace(' ', '', $tag);
                            $lowertag = strtolower($tag);
                            if (! empty($tag)){
                                echo "<a href='tags.php?name={$lowertag}'> #" . strtoupper($tag) . "</a> ";
                            }
                        }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <hr class="custom-hr">
    <?php if (isset($_SESSION['user'])){?>
        <!-- Start Add Comment -->
        <div class="row">
        <div class="offset-md-3">
            <div class="add-comment">
                <h3>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid='. $item['ItemID'];?>" method="POST">
                    <textarea name="comment" id="" cols="30" rows="10" required>
                    </textarea>
                    <input class="btn btn-primary" type="submit" value="Add Comment">
                </form>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $comment  = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                        $itemid   = $item['ItemID'];  
                        $userid   = $_SESSION['uid'];

                        if(! empty ($comment)){
                            $stmt = $conn->prepare("INSERT INTO
                                comments(`Comment`, `Status`, `CommentDate`, `Item_ID`, `User_ID`)
                                VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

                                $stmt->execute(array(
                                   'zcomment' =>$comment ,
                                   'zitemid'  =>$itemid ,
                                   'zuserid'  =>$userid
                                ));

                                if ($stmt){
                                    echo '<div class="alert alert-success">Comment Added </div>';
                                }else{
                                    echo '<div class="alert alert-danger">Comment was not Added </div>';  
                                }
                        }
                    }
                ?>
            </div>
        </div> 
        </div>
        <!-- End Add Comment -->
    <?php
        }else{
            echo '<a href="login.php">Login</a> OR <a href="login.php">Register</a> To Add Comment';
        }
    ?>
    <hr class="custom-hr">
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
                    Item_ID = ?
                AND 
                    Status = 1
                ORDER BY
                CommentID DESC");

                //execute statment
                $stmt->execute(array($item['ItemID']));

                //assign to variable
                $comments = $stmt->fetchAll();
                
           ?>
           <?php
           foreach ($comments as $comment){?>
           <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img class = "img-responsive  rounded-circle center-block" src="images/7.png" alt = "" />
                        <?php echo $comment['Member'] ?>
                        <!-- img-circle is no more used in bootstrap 4&5 instead use rounded-circle -->
                        <!-- https://stackoverflow.com/questions/41018489/bootstrap-4-img-circle-class-doesnt-seem-to-exist -->
                    </div>
                    <div class="col-sm-10">
                        <p class="lead"><?php echo $comment['Comment'] ?></p> 
                    </div>
                </div>
           </div>
           <hr class="custom-hr">  
        <?php } ?>
            
</div>
<?php
}else{
    echo 'There\' is no Such ID OR Item is Waiting Approval';
}

//include "includes/template/footer.php";
 include $tpl . 'footer.php';
 ob_end_flush(); // Release the output
?>