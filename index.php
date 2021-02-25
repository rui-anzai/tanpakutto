<?php
  include 'conn.php';
  include 'insert.php';
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <link rel="stylesheet" type="text/css" href="example.css">
        <meta charset="utf-8">
        <title>タンパクっと</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>

      <h1>タンパクっと</h1>
<div class="hidden_box">
<label for="label1">選択してください</label>
<input type="checkbox" id="label1"/>
<div class="hidden_show">
<!--非表示ここから-->
<table border="2">
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
</table>
<!--ここまで-->

</div>
</div>

      <!--<div class="aaaa">
      <div class="reol">-->

    </head>
    <body>
      <div class="aaaa">
      <div class="reol">
        
          <table border="1">
          <tbody>
          <tr><th>       
          <option value="who"></option>
      <?php
      //配列$products          
      foreach($total_products as $p){

      $total = $p['total'];
      $sum = $sum . '"'. $p['sum'].'",';
      $time = $time . '"'. $p['time'] .'",';   
      }  
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
	   <h2><?php echo $total; ?>グラム摂取しました</h2>
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
             labels: [<?php echo $time ?>],//各棒の名前（name)
          datasets: [
            {
              label: '直近一週間のタンパク質摂取量',
              data: [<?php echo $sum ?>],//各縦棒の高さ(値段)
               
              borderColor: "#fdbf64",
              backgroundColor: "rgba(0,0,0,0)"
            }
          ],
        },
        options: {
      scales: {
        xAxes: [{
          ticks: {
            autoSkip: true,
            maxTicksLimit: 7 //値の最大表示数
          }
        }]
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