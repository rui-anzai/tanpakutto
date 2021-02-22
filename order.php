<?php
	include 'db_config.php';
	if(!empty($_POST))
	{
		$product_id = $_POST['product_id'];
		$product_name = $_POST['product_name'];

		$order_quantity = 1;
		$order_sum = 0;

		try
		{
		 // 接続
		 $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		 // 注文内容をDBに保存する
		 $db->exec("INSERT INTO food_orders(product_id, order_quantity) VALUES({$product_id}, {$order_quantity})");

		 // 注文数を更新
		 $db->exec("UPDATE food_products SET order_quantity = order_quantity + {$order_quantity} WHERE id = {$product_id}");


		 //order_quantityに注文数を加算する
		 $stmt = $db->query("SELECT SUM(order_quantity) as sum FROM food_orders WHERE product_id = {$product_id}");
         $order_sum = $stmt->fetch(PDO::FETCH_ASSOC);
		 $order_sum = $order_sum['sum'];


		 $db = null;
		}
		catch(PDOException $e)
		{
	    	$error = $e->getMessage();
	    	exit;
		}
	}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>

      <h1>タンパクっと</h1>
	  	<h2>選択が完了しました</h2>
		<br><br>
		<a href="index.php">トップページに戻る</a>
	</body>
</html>
