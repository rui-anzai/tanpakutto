<?php
  include 'db_config.php';
  $products = array();
  $date_time = '';
  $total = '';
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

<?php
  $total_products = array();
  try
  {
  // mysql接続
   $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // 食品一覧を取得
   $stmt3 = $db->query("SELECT * FROM `chart` WHERE 1");
   $total_products = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        
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
