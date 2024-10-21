<?php
include('../products.php');
$oneProduct = new Products();
$id = $_GET['id'];
$oneProduct->getOneProduct($id);
?>
