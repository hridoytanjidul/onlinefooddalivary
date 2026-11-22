<?php
include("auth.php");

$form_id = (int) ($_GET['form_id'] ?? 0);
$allowedStatuses = array('in process', 'closed', 'rejected');
$notice = '';
$updated = false;

if (isset($_POST['update']) && !csrf_valid()) {
    $notice = CSRF_ERROR;
} elseif (isset($_POST['update'])) {
    $status = $_POST['status'] ?? '';
    $remark = trim($_POST['remark'] ?? '');

    if ($form_id <= 0) {
        $notice = 'Invalid order.';
    } elseif (!in_array($status, $allowedStatuses, true) || $remark === '') {
        $notice = 'Please choose a status and enter a message.';
    } else {
        // Prepared statements: a remark like "Rider's on the way" used to break the query.
        $stmt = $db->prepare("INSERT INTO remark(frm_id, status, remark) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $form_id, $status, $remark);
        $stmt->execute();

        $stmt = $db->prepare("UPDATE users_orders SET status = ? WHERE o_id = ?");
        $stmt->bind_param("si", $status, $form_id);
        $stmt->execute();

        $updated = true;
    }
}
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
    <title>Order Update</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

<style type="text/css" rel="stylesheet">
body {
  background: linear-gradient(135deg, #f8f9ff 0%, #eef3ff 100%);
  font-family: 'Open Sans', sans-serif;
}
.order-modal-wrap {
  max-width: 760px;
  margin: 50px auto;
  background: #ffffff;
  border: 1px solid rgba(92,74,199,0.12);
  border-radius: 26px;
  box-shadow: 0 20px 45px rgba(31, 41, 55, 0.08);
  padding: 28px 24px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
td {
  padding: 14px 12px;
  border-bottom: 1px solid rgba(111,122,145,0.12);
  color: #425068;
  font-size: 14px;
}
td:first-child {
  width: 180px;
  font-weight: 700;
  color: #1f2b3d;
}
select, textarea, input[type="submit"] {
  border-radius: 12px;
}
select, textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid rgba(111,122,145,0.22);
  background: #fff;
}
textarea {
  min-height: 120px;
}
.btn-primary {
  background: linear-gradient(135deg, #5c4ac7 0%, #4335a8 100%);
  border: none;
}
.btn-danger {
  background: linear-gradient(135deg, #ff7a59 0%, #ef5e43 100%);
  border: none;
}
</style>
</head>

<body>
<?php if ($updated) { ?>
<script type="text/javascript">
alert('Order status updated successfully');
// refresh the order page that opened this window so it shows the new status
if (window.opener && !window.opener.closed) { window.opener.location.reload(); }
</script>
<?php } elseif ($notice !== '') { ?>
<script type="text/javascript">alert(<?php echo json_encode($notice); ?>);</script>
<?php } ?>

<div class="order-modal-wrap">
 <form name="updateticket" id="updatecomplaint" method="post">
 <?php echo csrf_field(); ?> 
<table border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td><b>Form Number</b></td>
      <td><?php echo $form_id; ?></td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
   
    <tr>
      <td><b>Status</b></td>
      <td><select name="status" required="required">
      <option value="">Select Status</option>
      <option value="in process">On the way</option>
      <option value="closed">Delivered</option>
      <option value="rejected">Cancelled</option>
      </select></td>
    </tr>

    <tr>
      <td><b>Message</b></td>
      <td><textarea name="remark" required="required" placeholder="Add a delivery update or note..."></textarea></td>
    </tr>

    <tr>
      <td><b>Action</b></td>
      <td><input type="submit" name="update" class="btn btn-primary" value="Submit">
      <input name="Submit2" type="button" class="btn btn-danger" value="Close this window" onclick="f2();" style="cursor: pointer;" /></td>
    </tr>
</table>
 </form>
</div>

</body>
</html>