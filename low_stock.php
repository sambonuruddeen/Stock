<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; 




?>
<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
          <li><a href="dashboard.php">Home</a></li>       
          <li class="active">Low Stock</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-list"></i> Low Stock</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">
                
                <table class="table" id="manageProductTable">
                    <thead>
                        <tr>
                            <th style="width:10%;">Photo</th>                           
                            <th>Product Number</th>
                            <th>Product Name</th>
                            <th>Price</th>                          
                            <th>Quantity</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
$lowStockSql = "SELECT product.product_id, product.product_number, product.product_name, product.product_image, product.brand_id, product.categories_id, product.quantity, product.rate, product.active, product.status, brands.brand_name, categories.categories_name FROM product 
        INNER JOIN brands ON product.brand_id = brands.brand_id 
        INNER JOIN categories ON product.categories_id = categories.categories_id  
        WHERE product.quantity <= 10 AND product.status = 1";

$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$active = ""; 

while($row = $lowStockQuery->fetch_array()) {

    if($row[8] == 1) {
        // activate member
        $active = "<label class='label label-success'>Available</label>";
    } else {
        // deactivate member
        $active = "<label class='label label-danger'>Not Available</label>";
    } // /else
    
                        ?>
                        <tr>
                            <td><?php 
                            $imageUrl = substr($row[3], 3);
                            echo "<img class='img-round' src='".$imageUrl."' style='height:30px; width:50px;'  />"; ?></td>
                            <td><?php echo $row[1]; ?></td>
                            <td><?php echo $row[2]; ?></td>
                            <td><?php echo $row[7]; ?></td>
                            <td><strong class="text-danger"><?php echo $row[6]; ?></strong></td>
                            <td><?php echo $row[10]; ?></td>
                            <td><?php echo $row[11]; ?></td>
                            <td><?php echo $active; ?></td>
                            
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <!-- /table -->

            </div>

        </div>

    </div>
</div>
