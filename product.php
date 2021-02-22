<?php
	include 'db_config.php';

	$product = array();

	try
	{
	 // connect
	 $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
	 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     //index.phpで選択したidを受け取る
	 $stmt = $db->query("SELECT * FROM food_products WHERE id={$_GET['id']}");
	 $product = $stmt->fetch(PDO::FETCH_ASSOC);

	 $id = $product['id'];
	 $name = $product['food_name'];
	 $protein = $product['protein'];
	 $order = $product['order_quantity'];

	 $db = null;
	}
	catch(PDOException $e)
	{
	echo $e->getMessage();
	exit;
	}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <titleタンパクっと</title>
    </head>

    <body>

		<h1></h1>


	    <h2><?php echo $name; ?></h2>

        <p><?php echo $protein; ?>g</p>
				<p>選択数: <?php echo $order;?></p>

        <form action="order.php" method="POST">
            
					<input name="product_id" type="hidden" value="<?php echo $id; ?>">
					<input name="product_name" type="hidden" value="<?php echo $name; ?>">
          <button name="order" type="submit">選択する</button>
        </form>

	</body>
</html>
