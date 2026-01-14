<?php
include 'include/admin_auth.php';
include '../includes/db-connect.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

$sql = "
    SELECT 
        p.title,
        oi.price,
        oi.quantity
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
";

$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<table width="100%" border="1" cellpadding="8">
<tr>
  <th>Product</th>
  <th>Price</th>
  <th>Qty</th>
  <th>Subtotal</th>
</tr>

<?php while ($row = $result->fetch_assoc()):
  $subtotal = $row['price'] * $row['quantity'];
  $total += $subtotal;
?>
<tr>
  <td><?= htmlspecialchars($row['title']) ?></td>
  <td>$<?= number_format($row['price'],2) ?></td>
  <td><?= $row['quantity'] ?></td>
  <td>$<?= number_format($subtotal,2) ?></td>
</tr>
<?php endwhile; ?>

<tr>
  <td colspan="3"><strong>Total</strong></td>
  <td><strong>$<?= number_format($total,2) ?></strong></td>
</tr>
</table>
