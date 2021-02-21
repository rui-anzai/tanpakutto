<?php
  include 'db_config.php';
  $products = array();
  try
  {
     // mysql接続
     $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


     // 食品一覧を取得
     $stmt = $db->query("SELECT * FROM food_products");
     $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
     //接続を切る
     //$db = null;
  }
  catch(PDOException $e)
  {
   echo $e->getMessage();
   exit;
     $db = null;//接続を切る
  }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>タンパクっと</title>
    </head>

    <body>

      <h1>タンパクっと</h1>

        <table border="1">
          <tr><th>食品</th><th>タンパク質</th><th>選択数</th></tr>
      <?php
      //配列$products          
      foreach($products as $p){
      $id = $p['id'];
      $name = $p['food_name'];
      $protein = $p['protein'];
      $order = $p['order_quantity'];
      //表を生成して選択に合わせてidを送信  
      echo "<tr><td><a href='product.php?id={$id}'>{$name}</a></td><td>{$protein}グラム</td><td>{$order}個</td></tr>";     
      }
     ?>
	</body>
</html>
<?php
  
	try
	{
   //food_productsデーブル内のproteinとorder..の合計を計算
	 $stmt2 = $db->query("SELECT *, SUM(protein * order_quantity) FROM food_products");
	 $food_product = $stmt2->fetch(PDO::FETCH_ASSOC);
   //変数$goukeiに$stmt2の計算結果を格納
	 $goukei = $food_product['SUM(protein * order_quantity)'];

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
    </head>

    <body>

	    <h2><?php echo $goukei; ?>グラム摂取しました</h2>
      <?php 
      //更新ボタン
      if(isset($_POST['add'])) {
          echo "";
      } else if(isset($_POST['update'])) {
          	try
          	{
          	// DB接続
          	$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
          	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
          	$db->exec("UPDATE `food_products` SET `order_quantity`=0 WHERE 1");
          	
          	}
          	catch(PDOException $e)
          	{
          	    $error = $e->getMessage();
          	    exit;
          	}

          //UPDATE `food_products` SET `order_quantity`=0 WHERE 1
      } else {
          echo "";
      }
      ?>
      
  <form action="index.php" method="post">
    <button type="submit" name="add">登録</button>
    <button type="submit" name="update">更新</button>
    <button type="submit" name="remove">削除</button>
  </form> 

	</body>
</html>