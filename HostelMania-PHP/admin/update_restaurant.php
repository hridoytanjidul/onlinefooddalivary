<?php
include("auth.php");

$resId = (int) ($_GET['res_upd'] ?? 0);
$error = '';
$success = '';

$stmt = $db->prepare("SELECT * FROM restaurant WHERE rs_id = ?");
$stmt->bind_param("i", $resId);
$stmt->execute();
$restaurant = $stmt->get_result()->fetch_assoc();
if (!$restaurant) {
    header("Location: all_restaurant.php");
    exit;
}
$form = array(
    'res_name' => $restaurant['title'], 'email' => $restaurant['email'], 'phone' => $restaurant['phone'],
    'url' => $restaurant['url'], 'o_hr' => $restaurant['o_hr'], 'c_hr' => $restaurant['c_hr'],
    'o_days' => $restaurant['o_days'], 'c_name' => (int) $restaurant['c_id'], 'address' => $restaurant['address'],
);

if (isset($_POST['submit']) && !csrf_valid()) {
    $error = admin_alert('danger', '<strong>' . CSRF_ERROR . '</strong>');
} elseif (isset($_POST['submit'])) {
    $form = array(
        'res_name' => trim($_POST['res_name'] ?? ''),
        'email'    => trim($_POST['email'] ?? ''),
        'phone'    => trim($_POST['phone'] ?? ''),
        'url'      => trim($_POST['url'] ?? ''),
        'o_hr'     => trim($_POST['o_hr'] ?? ''),
        'c_hr'     => trim($_POST['c_hr'] ?? ''),
        'o_days'   => trim($_POST['o_days'] ?? ''),
        'c_name'   => (int) ($_POST['c_name'] ?? 0),
        'address'  => trim($_POST['address'] ?? ''),
    );

    if ($form['c_name'] <= 0 || $form['res_name'] === '' || $form['email'] === '' || $form['phone'] === '' || $form['url'] === ''
        || $form['o_hr'] === '' || $form['c_hr'] === '' || $form['o_days'] === '' || $form['address'] === '') {
        $error = admin_alert('danger', '<strong>All fields Must be Fillup!</strong>');
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $error = admin_alert('danger', '<strong>Invalid email address!</strong>');
    } else {
        // The image is optional when editing: keep the current one if no new file is chosen.
        $image = $restaurant['image'];
        if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadError = null;
            $image = save_uploaded_image($_FILES['file'], __DIR__ . '/Res_img', $uploadError);
            if ($image === null) {
                $error = admin_alert('danger', '<strong>' . e($uploadError) . '</strong>');
            }
        }

        if ($error === '') {
            $stmt = $db->prepare("UPDATE restaurant SET c_id = ?, title = ?, email = ?, phone = ?, url = ?, o_hr = ?, c_hr = ?, o_days = ?, address = ?, image = ? WHERE rs_id = ?");
            $stmt->bind_param("isssssssssi", $form['c_name'], $form['res_name'], $form['email'], $form['phone'], $form['url'], $form['o_hr'], $form['c_hr'], $form['o_days'], $form['address'], $image, $resId);
            $stmt->execute();
            $restaurant['image'] = $image;

            $success = admin_alert('success', '<strong>Record Updated!</strong>');
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
    <link rel="icon" type="image/png" sizes="16x16" href="images/icn.png">
    <title>Update Restaurant</title>
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
      
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> </div>
            </div>
         
            <div class="container-fluid">
     
                  
									
									<?php  echo $error;
									        echo $success; ?>
									
									
								
								
					    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            
                                <h4 class="m-b-0 ">Update Restaurant</h4>
                            
                            <div class="card-body">
                                <form action='' method='post'  enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-body">
                                       
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Restaurant Name</label>
                                                    <input type="text" name="res_name" value="<?php echo e($form['res_name']); ?>" class="form-control" placeholder="John doe">
                                                   </div>
                                            </div>
                                   
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Bussiness E-mail</label>
                                                    <input type="text" name="email" value="<?php echo e($form['email']); ?>" class="form-control form-control-danger" placeholder="example@gmail.com">
                                                    </div>
                                            </div>
                                        
                                        </div>
                                     
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phone </label>
                                                    <input type="text" name="phone" class="form-control" value="<?php echo e($form['phone']); ?>" placeholder="1-(555)-555-5555">
                                                   </div>
                                            </div>
                         
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">website URL</label>
                                                    <input type="text" name="url" class="form-control form-control-danger" value="<?php echo e($form['url']); ?>" placeholder="http://example.com">
                                                    </div>
                                            </div>
                                       
                                        </div>
                                
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Open Hours</label>
                                                    <select name="o_hr" class="form-control custom-select"  data-placeholder="Choose a Category" >
                                                        <option value="">--Select your Hours--</option>
                                                        <?php echo select_options($OPEN_HOURS, $form['o_hr']); ?>
                                                    </select>
                                                </div>
                                            </div>
                                      
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Close Hours</label>
                                                    <select name="c_hr" class="form-control custom-select"    data-placeholder="Choose a Category" >
                                                        <option value="">--Select your Hours--</option>
                                                        <?php echo select_options($CLOSE_HOURS, $form['c_hr']); ?>
                                                    </select>
                                                </div>
                                            </div>
											
											 <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Open Days</label>
                                                    <select name="o_days" class="form-control custom-select"  data-placeholder="Choose a Category" tabindex="1">
                                                        <option value="">--Select your Days--</option>
                                                        <?php echo select_options($OPEN_DAYS, $form['o_days']); ?>
                                                    </select>
                                                </div>
                                            </div>
											
											
											<div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Image</label>
                                                    <input type="file" name="file" id="lastName" class="form-control form-control-danger" accept="image/png,image/jpeg,image/gif">
                                                    <small class="form-text text-muted">Current: <img src="Res_img/<?php echo e($restaurant['image']); ?>" alt="" style="max-height:40px;"> Leave empty to keep it.</small>
                                                    </div>
                                            </div>
                                   
											
											 <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Select Category</label>
													<select name="c_name" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                        <option value="">--Select Category--</option>
                                                        <?php
                                                    $res = mysqli_query($db, "SELECT c_id, c_name FROM res_category ORDER BY c_name");
                                                    while ($rows = mysqli_fetch_array($res)) {
                                                        $selected = ((int) $form['c_name'] === (int) $rows['c_id']) ? ' selected' : '';
                                                        echo '<option value="' . (int) $rows['c_id'] . '"' . $selected . '>' . e($rows['c_name']) . '</option>';
                                                    }
                                                 ?>
                                                    </select>
                                                </div>
                                            </div>
											
											
											
											
                                        </div>
                                       
                                        <h3 class="box-title m-t-40">Restaurant Address</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    
                                                    <textarea name="address" style="height:100px;" class="form-control"><?php echo e($form['address']); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                      
                                  
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Save"> 
                                        <a href="all_restaurant.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
					<footer class="footer"> © 2025 - HostelMania </footer>
                </div>
       
            </div>
            
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