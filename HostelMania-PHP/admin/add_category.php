<?php
include("auth.php");

$error = '';
$success = '';
if (isset($_POST['submit']) && !csrf_valid()) {
    $error = admin_alert('danger', '<strong>' . CSRF_ERROR . '</strong>');
} elseif (isset($_POST['submit'])) {
    $categoryName = trim($_POST['c_name'] ?? '');

    if ($categoryName === '') {
        $error = admin_alert('danger', '<strong>field Required!</strong>');
    } else {
        $check_cat = $db->prepare("SELECT c_id FROM res_category WHERE c_name = ?");
        $check_cat->bind_param("s", $categoryName);
        $check_cat->execute();
        $check_cat->store_result();

        if ($check_cat->num_rows > 0) {
            $error = admin_alert('danger', '<strong>Category already exist!</strong>');
        } else {
            $mql = $db->prepare("INSERT INTO res_category(c_name) VALUES(?)");
            $mql->bind_param("s", $categoryName);
            $mql->execute();

            $success = admin_alert('success', 'New Category Added Successfully.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">   
    <title>Add Category</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
</head>

<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <div id="main-wrapper">

        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        
                        <span><img src="images/icn.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
      
                    <ul class="navbar-nav mr-auto mt-md-0">

                    </ul>
           
                    <ul class="navbar-nav my-lg-0">

        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
     
        <div class="left-sidebar">
    
            <div class="scroll-sidebar">
           
                <nav class="sidebar-nav">
                   <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        <li class="nav-label">Log</li>
                        <li> <a href="all_users.php">  <span><i class="fa fa-user f-s-20 "></i></span><span>Users</span></a></li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="all_restaurant.php">All Restaurants</a></li>
								<li><a href="add_category.php">Add Category</a></li>
                                <li><a href="add_restaurant.php">Add Restaurant</a></li>
                                
                            </ul>
                        </li>
                     <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="all_menu.php">All Menues</a></li>
								<li><a href="add_menu.php">Add Menu</a></li>
                              
                                
                            </ul>
                        </li>
						 <li> <a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>
                         
                    </ul>
                </nav>
        
            </div>
         
        </div>

        <div class="page-wrapper">

            <div class="container-fluid">
     
                     
					
					
					  <div class="row">
                   
                   
					
					 <div class="container-fluid">
         
                  
									
									<?php  
									        echo $error;
									        echo $success; ?>
									
									
								
						
                         
                            <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Add Restaurant Category</h4>
                            </div>
                                <form action='' method='post' >
                                    <?php echo csrf_field(); ?>
                                    <div class="form-body">
                                       
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Category</label>
                                                    <input type="text" name="c_name" class="form-control" >
                                                   </div>
                                            </div>
                                     
                                            
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Save"> 
                                        <a href="add_category.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
					
                </div>
					
					   <div class="col-12">
                        
                       
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Listed Categories</h4>
                             
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-hover table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Category Name</th>
                                                <th>Date</th>
                                              
												  <th>Action</th>
												 
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
											
											<?php
												$sql = "SELECT * FROM res_category ORDER BY c_id DESC";
												$query = mysqli_query($db, $sql);

												if (mysqli_num_rows($query) == 0) {
													echo '<tr><td colspan="4"><center>No Categories-Data!</center></td></tr>';
												} else {
													while ($rows = mysqli_fetch_array($query)) {
														echo '<tr><td>' . (int) $rows['c_id'] . '</td>
															<td>' . e($rows['c_name']) . '</td>
															<td>' . e($rows['date']) . '</td>
															<td>' . delete_button('delete_category.php', 'cat_del', $rows['c_id'], 'Delete this category?', 'btn btn-danger btn-flat btn-addon btn-xs m-b-10', 'font-size:16px') . '
																<a href="update_category.php?cat_upd=' . (int) $rows['c_id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i class="fa fa-edit"></i></a>
															</td></tr>';
													}
												}
												?>
                                             
                                            
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
						 </div>
					
					
					
					
					
					
					
                </div>
               
            </div>
       
            <footer class="footer"> © 2025 - HostelMania </footer>
      
        </div>
   
    </div>
   
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>   
    <script src="js/sidebarmenu.js"></script>  
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>