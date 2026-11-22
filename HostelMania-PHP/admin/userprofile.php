<?php
// Popup opened from view_order.php, so it must check the ADMIN session
// (it used to check the customer session and bounced admins to the shop login).
include("auth.php");

$orderId = (int) ($_GET['newform_id'] ?? 0);
$stmt = $db->prepare("SELECT users.* FROM users_orders INNER JOIN users ON users.u_id = users_orders.u_id WHERE users_orders.o_id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript">
function f2()
{
window.close();
}
function f3()
{
window.print();
}
</script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/icn.png">
    <title>User Profile</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

<style type="text/css">

table { 
	width: 650px; 
	border-collapse: collapse; 
	margin: auto;
	margin-top:50px;
	}

tr:nth-of-type(odd) { 
	background: #eee; 
	}

th { 
	background: #004684; 
	color: white; 
	font-weight: bold; 
	}

td, th { 
	padding: 10px; 
	border: 1px solid #ccc; 
	text-align: left; 
	font-size: 14px;
	}

	</style>
</head>

<body>

<div style="margin-left:50px;">
<table border="0" cellspacing="0" cellpadding="0">
<?php if (!$row) { ?>
    <tr>
      <td colspan="2"><b>User not found.</b></td>
    </tr>
<?php } else { ?>
    <tr>
      <td colspan="2"><b><?php echo e($row['f_name']); ?>'s profile</b></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr height="50">
      <td><b>Reg Date:</b></td>
      <td><?php echo e($row['date']); ?></td>
    </tr>
    <tr height="50">
      <td><b>First Name:</b></td>
      <td><?php echo e($row['f_name']); ?></td>
    </tr>
    <tr height="50">
      <td><b>Last Name:</b></td>
      <td><?php echo e($row['l_name']); ?></td>
    </tr>
    <tr height="50">
      <td><b>User Email:</b></td>
      <td><?php echo e($row['email']); ?></td>
    </tr>
    <tr height="50">
      <td><b>User Phone:</b></td>
      <td><?php echo e($row['phone']); ?></td>
    </tr>
    <tr height="50">
      <td><b>Address:</b></td>
      <td><?php echo e($row['address']); ?></td>
    </tr>
    <tr height="50">
      <td><b>Status:</b></td>
      <td><?php if ($row['status'] == 1) {
          echo "<div class='btn btn-primary'>Active</div>";
      } else {
          echo "<div class='btn btn-danger'>Block</div>";
      } ?></td>
    </tr>
<?php } ?>
    <tr>
      <td colspan="2">
      <input name="Submit2" type="button" class="btn btn-danger" value="Close this window" onclick="f2();" style="cursor: pointer;" /></td>
    </tr>
</table>
</div>

</body>
</html>
