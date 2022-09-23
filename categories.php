<?php
 ob_start();
 session_start();
 include 'init.php';
?>

        <?php
        if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
            $category = intval($_GET['pageid']);
            $catid = $_GET['pageid'];
            $catName = getAllFrom("Name", "categories", "WHERE CatID = {$category} ", "", "CatID" );
            echo '<div class="container">';
            foreach($catName as $cat){
            echo '<h1 class="text-center">' . $cat['Name'] . '</h1>';
            }
            echo '<div class="row">';


            $allItems = getAllFrom("*", "items", "WHERE Cat_ID = {$category} ", "AND Approve = 1", "ItemID" );
            foreach($allItems as $item){
                $imgsource = empty($item['Image'])? 'admin/uploads/items/default/pill3.png' : "admin/uploads/items/" . $item['Image'];
                echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class = "thumbnail item-card">';
                        echo '<span class="price-tag">' . $item['Price'] . '</span>';
                        echo '<img class = "img-responsive" src="' . $imgsource . '" alt = "" />';
                        echo '<div class = "caption">';
                            echo '<h3><a href="items.php?itemid=' . $item['ItemID'] . ' ">' . $item['Name'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['AddDate'] . '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        }else {
            echo 'You Must Add Page ID';
        }
        ?>
    </div>
</div>



<?php
//include "includes/template/footer.php";
 include $tpl . 'footer.php';
 ob_end_flash();
?>