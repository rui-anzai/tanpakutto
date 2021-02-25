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
<!DOCTYPE html>
<html lang="ja">
    <head>
        <link rel="stylesheet" type="text/css" href="example.css">
        <meta charset="utf-8">
        <title>タンパクっと</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>

    </head>

    <body>

      <h1>タンパクっと</h1>
      <div class="aaaa">
      <div class="reol">
          <table border="1">
          <tbody>
          <tr><th>       
          <option value="who"></option>
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
     <!--円グラフのclassを定義
     <div class="chart-wrap" style="position: relative; display: inline-block;　display:flex; width: 950px; height: 700px;">
     <canvas id="myPieChart"></canvas>
     </div>
     <br></br>-->

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
      <?php
      try
      {
      // db接続
      $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
      $db->exec("INSERT INTO `chart`(`total`, `date_time`) VALUES ($goukei,NOW())"); //chartテーブルに$goukeiと現在の時間をinsert
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
      <?php
      //配列$products          
      foreach($total_products as $p){
      //
      $total = $total . '"'. $p['total'].'",';
      $date_time = $date_time . '"'. $p['date_time'] .'",';   
      }  
     ?>

	    <h2><?php echo $goukei; ?>グラム摂取しました</h2>
      <?php 
      //更新ボタン
      if(isset($_POST['add'])) {
          echo "";
      } else if(isset($_POST['update'])) {
          	try
          	{
          	// db接続
          	$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
          	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
          	$db->exec("UPDATE `food_products` SET `order_quantity`=0 WHERE 1"); //注文数を0にリセット
          	
          	}
          	catch(PDOException $e)
          	{
          	    $error = $e->getMessage();
          	    exit;
          	}

      } else {
          echo "";
      }
      ?>
      
         <!--棒グラフの表示-->
     <h2>直近一週間</h2>    
      <div class="chart-container" style="position: relative; width: 950px; height: 700px;">
          <canvas id="myLineChart">ここにチャート表示</canvas>
      </div>
     <script>

     //.getContext('2d');はcanvasでグラフとか描画するために使う 
     var cty = document.getElementById("myLineChart").getContext('2d');
      var myLineChart = new Chart(cty, {
        type: 'line',
        data: {
             labels: [<?php echo $date_time ?>],//各棒の名前（name)
          datasets: [
            {
              label: '直近一週間のタンパク質摂取量',
              data: [<?php echo $total ?>],//各縦棒の高さ(値段)
               
              borderColor: "#fdbf64",
              backgroundColor: "rgba(0,0,0,0)"
            }
          ],
        },
        options: {
          title: {
            display: true,
           
          }
        }
      });
      </script>
      
  <!--index.phpにpost-->
  <form action="index.php" method="post">
    <button type="submit" name="add">登録</button>
    <button type="submit" name="update">更新</button>
    <button type="submit" name="remove">削除</button>
  </form> 


	</body>
</html>
