<?php
include 'include/admin_auth.php';
include '../includes/db-connect.php';

// إضافة منتج جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = $connect->real_escape_string($_POST['title']);
    $price = (float)$_POST['price'];

    $image_path = '';
    if (!empty($_FILES['image_file']['name'])) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $tmp_name = $_FILES['image_file']['tmp_name'];
        $filename = basename($_FILES['image_file']['name']);
        $target_file = $upload_dir . time() . '_' . $filename;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $image_path = 'uploads/' . time() . '_' . $filename;
        } else {
            echo "<p style='color:red'>Error uploading image.</p>";
        }
    }

    $connect->query("INSERT INTO products (title, price, image_url) VALUES ('$title', $price, '$image_path')");
}

// تعديل منتج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = (int)$_POST['edit_id'];
    $title = $connect->real_escape_string($_POST['title']);
    $price = (float)$_POST['price'];

    $image_path = '';
    if (!empty($_FILES['image_file']['name'])) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $tmp_name = $_FILES['image_file']['tmp_name'];
        $filename = basename($_FILES['image_file']['name']);
        $target_file = $upload_dir . time() . '_' . $filename;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $image_path = 'uploads/' . time() . '_' . $filename;
        }
    }

    if ($image_path) {
        $connect->query("UPDATE products SET title='$title', price=$price, image_url='$image_path' WHERE id=$id");
    } else {
        $connect->query("UPDATE products SET title='$title', price=$price WHERE id=$id");
    }
}

// حذف منتج
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = $connect->query("SELECT image_url FROM products WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $img = $res->fetch_assoc()['image_url'];
        if ($img && file_exists('../' . $img)) unlink('../' . $img);
    }
    $connect->query("DELETE FROM products WHERE id=$id");
}

// جلب جميع المنتجات
$result = $connect->query("SELECT * FROM products");
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products - Admin</title>
<link rel="stylesheet" href="assets/admin.css">
<style>
table {border-collapse: collapse; width: 100%;}
table, th, td {border: 1px solid #ccc;}
th, td {padding: 8px; text-align: center;}
img {max-width: 70px; max-height: 50px;}
form {margin:0;}
.return-btn {margin-bottom: 15px; padding: 8px 15px; background:#555; color:#fff; border:none; cursor:pointer;}
</style>
</head>
<body>
<h1>Manage Products</h1>

<button class="return-btn" onclick="window.location.href='index.php'">Return to Index</button>

<h2>Add New Product</h2>
<form method="POST" enctype="multipart/form-data">
  <input type="text" name="title" placeholder="Title" required>
  <input type="number" step="0.01" name="price" placeholder="Price" required>
  <input type="file" name="image_file" accept="image/*">
  <button type="submit" name="add">Add Product</button>
</form>

<h2>Existing Products</h2>
<table>
<tr>
<th>ID</th><th>Title</th><th>Price</th><th>Image</th><th>Actions</th>
</tr>
<?php foreach ($products as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['title']) ?></td>
  <td>$<?= number_format($p['price'],2) ?></td>
  <td>
    <?php if ($p['image_url']): ?>
      <img src="../<?= htmlspecialchars($p['image_url']) ?>" alt="Product Image">
    <?php endif; ?>
  </td>
  <td>
    <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
    <form method="POST" enctype="multipart/form-data" style="display:inline-block;">
      <input type="hidden" name="edit_id" value="<?= $p['id'] ?>">
      <input type="text" name="title" value="<?= htmlspecialchars($p['title']) ?>" required>
      <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required>
      <input type="file" name="image_file" accept="image/*">
      <button type="submit">Edit</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
