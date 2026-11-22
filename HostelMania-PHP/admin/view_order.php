<?php
include("auth.php");

$orderId = (int) ($_GET['user_upd'] ?? 0);
$stmt = $db->prepare("SELECT users.username, users.address, users_orders.* FROM users INNER JOIN users_orders ON users.u_id = users_orders.u_id WHERE users_orders.o_id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$rows = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/icn.png">
    <title>View Order</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
<script type="text/javascript">
var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
 if(popUpWin)
{
if(!popUpWin.closed) popUpWin.close();
}
popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width=' + (width || 1000) + ',height=' + (height || 1000) + ',left=' + (left || 0) + ',top=' + (top || 0));
}

</script>
</head>

<body class="fix-header fix-sidebar">
    
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
                           
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                 
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
                    <div class="col-12">
                        
                   
                        <div class="col-lg-12">
                        <div class="card card-outline-primary admin-table-card">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">View Order</h4>
                            </div>
                             
                                <div class="table-responsive m-t-20">
                                    <table id="myTable" class="table table-bordered table-striped table-modern">
                                       
                                        <tbody>
<?php if (!$rows) { ?>
                                            <tr><td colspan="3"><center>Order not found.</center></td></tr>
<?php } else {
    $statusBadges = array(
        'in process' => '<button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span> On The Way!</button>',
        'closed'     => '<button type="button" class="btn btn-primary"><span class="fa fa-check-circle" aria-hidden="true"></span> Delivered</button>',
        'rejected'   => '<button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelled</button>',
    );
    $badge = $statusBadges[(string) $rows['status']] ?? '<button type="button" class="btn btn-info"><span class="fa fa-bars" aria-hidden="true"></span> Dispatch</button>';
?>
                                            <tr>
                                                <td><strong>Username:</strong></td>
                                                <td><center><?php echo e($rows['username']); ?></center></td>
                                                <td><center>
                                                    <a href="javascript:void(0);" onclick="popUpWindow('order_update.php?form_id=<?php echo (int) $rows['o_id']; ?>');" title="Update order">
                                                        <button type="button" class="btn btn-primary">Update Order Status</button></a>
                                                </center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Title:</strong></td>
                                                <td><center><?php echo e($rows['title']); ?></center></td>
                                                <td><center>
                                                    <a href="javascript:void(0);" onclick="popUpWindow('userprofile.php?newform_id=<?php echo (int) $rows['o_id']; ?>');" title="View user details">
                                                        <button type="button" class="btn btn-primary">View User Details</button></a>
                                                </center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Quantity:</strong></td>
                                                <td colspan="2"><center><?php echo e($rows['quantity']); ?></center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Price:</strong></td>
                                                <td colspan="2"><center>$<?php echo e($rows['price']); ?></center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total:</strong></td>
                                                <td colspan="2"><center>$<?php echo number_format($rows['price'] * $rows['quantity'], 2); ?></center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Address:</strong></td>
                                                <td colspan="2"><center><?php echo e($rows['address']); ?></center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date:</strong></td>
                                                <td colspan="2"><center><?php echo e($rows['date']); ?></center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td colspan="2"><center><?php echo $badge; ?></center></td>
                                            </tr>
<?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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