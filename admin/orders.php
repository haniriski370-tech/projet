<?php
include 'include/admin_auth.php';
include '../includes/db-connect.php';

$sql = "
    SELECT 
        o.id AS order_id,
        o.user_id,
        u.username,
        o.total,
        o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
";

$result = $connect->query($sql);
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders</title>

<style>
table { width:100%; border-collapse:collapse; }
th, td { padding:10px; border:1px solid #ccc; text-align:left; }
button { padding:6px 12px; cursor:pointer; }

.modal {
  display:none;
  position:fixed;
  top:0; left:0;
  width:100%; height:100%;
  background:rgba(0,0,0,.6);
}

.modal-content {
  background:#fff;
  width:600px;
  margin:80px auto;
  padding:20px;
  position:relative;
}

.close {
  position:absolute;
  top:10px; right:15px;
  font-size:20px;
  cursor:pointer;
}
</style>
</head>

<body>

<h1>Orders</h1>

<table>
<tr>
  <th>Order ID</th>
  <th>User</th>
  <th>Total</th>
  <th>Date</th>
  <th>Action</th>
</tr>

<?php foreach ($orders as $o): ?>
<tr>
  <td>#<?= $o['order_id'] ?></td>
  <td><?= htmlspecialchars($o['username']) ?> (ID: <?= $o['user_id'] ?>)</td>
  <td>$<?= number_format($o['total'],2) ?></td>
  <td><?= $o['created_at'] ?></td>
  <td>
    <button onclick="viewOrder(<?= $o['order_id'] ?>)">View</button>
  </td>
</tr>
<?php endforeach; ?>
</table>

<!-- MODAL -->
<div class="modal" id="orderModal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">✖</span>
    <h2>Order Details</h2>
    <div id="orderDetails">Loading...</div>
  </div>
</div>

<script>
function viewOrder(orderId) {
  document.getElementById('orderModal').style.display = 'block';

  fetch('order_details.php?order_id=' + orderId)
    .then(res => res.text())
    .then(html => {
      document.getElementById('orderDetails').innerHTML = html;
    });
}

function closeModal() {
  document.getElementById('orderModal').style.display = 'none';
}
</script>

</body>
</html>
