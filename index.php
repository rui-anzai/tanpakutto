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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.0/chartjs-plugin-annotation.js"></script>


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

    </head>
    <body>
      <div class="aaaa">
      <div class="reol">
        
          <table border="1">
          <tbody>
          <tr><th>       
          <option value="who"></option>
      <?php
      foreach($total_products as $p){

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
     <h2>直近一週間のグラフ</h2> 
     <h2><?php echo (int)$goukei; ?>グラム摂取しました</h2>
         <!--棒グラフの表示-->   
      <div class="chart-container" style="position: relative; width: 950px; height: 700px;">
          <canvas id="myLineChart">ここにチャート表示</canvas>
      </div>
     <script>
     //.getContext('2d');はcanvasでグラフとか描画するために使う 
     var cty = document.getElementById("myLineChart").getContext('2d');
      var myLineChart = new Chart(cty, {
        type: 'bar',
        data: {
             labels: [<?php echo $time ?>],//各棒の名前（name)
          datasets: [
            {
              label: '直近一週間のタンパク質摂取量',
              data: [<?php echo $sum ?>],//各縦棒の高さ(値段)
               
              backgroundColor:[
              "rgba(255, 99, 132, 0.2)",
              "rgba(255, 159, 64, 0.2)",
              "rgba(255, 205, 86, 0.2)",
              "rgba(75, 192, 192, 0.2)",
              "rgba(54, 162, 235, 0.2)",
              "rgba(153, 102, 255, 0.2)",
              "rgba(201, 203, 207, 0.2)"
            ],
            borderColor: [
              "rgb(255, 99, 132)",
              "rgb(255, 159, 64)",
              "rgb(255, 205, 86)",
              "rgb(75, 192, 192)",
              "rgb(54, 162, 235)",
              "rgb(153, 102, 255)",
              "rgb(201, 203, 207)"
            ],
            }
          ],
        },
        options: {
      scales: {
        xAxes: [{
          id : 'x軸',
          ticks: {
            autoSkip: true,
            maxTicksLimit: 7 //値の最大表示数
          }
        }],
        yAxes: [{
          id : 'y軸',
        }]
      },
      annotation: {
        annotations: [
            {
                type: 'line', // 線分を指定
                drawTime: 'afterDatasetsDraw',
                id: 'a-line-1', // 線のid名を指定（他の線と区別するため）
                mode: 'horizontal', // 水平を指定
                scaleID: 'y左軸', // 基準とする軸のid名
                value: 15.6, // 引きたい線の数値（始点）
                endValue: 15.6, // 引きたい線の数値（終点）
                borderColor: 'red', // 線の色
                borderWidth: 3, // 線の幅（太さ）
                borderDash: [2, 2],
                borderDashOffset: 1,
                label: { // ラベルの設定
                    backgroundColor: 'rgba(255,255,255,0.8)',
                    bordercolor: 'rgba(200,60,60,0.8)',
                    borderwidth: 2,
                    fontSize: 10,
                    fontStyle: 'bold',
                    fontColor: 'rgba(200,60,60,0.8)',
                    xPadding: 10,
                    yPadding: 10,
                    cornerRadius: 3,
                    position: 'left',
                    xAdjust: 0,
                    yAdjust: 0,
                    enabled: true,
                    content: '平均気温(2019) 15.6℃'
                }
            },
            {
                type: 'line', // 線分を指定
                drawTime: 'afterDatasetsDraw',
                id: 'a-line-2', // 線のid名を指定（他の線と区別するため）
                mode: 'horizontal', // 水平を指定
                scaleID: 'y左軸', // 基準とする軸のid名
                value: 13.7, // 引きたい線の数値（始点）
                endValue: 13.7, // 引きたい線の数値（終点）
                borderColor: 'rgba(60,200,60,0.8)', // 線の色
                borderWidth: 3, // 線の幅（太さ）
                borderDash: [2, 2],
                borderDashOffset: 1,
                label: { // ラベルの設定
                    backgroundColor: 'rgba(255,255,255,0.8)',
                    bordercolor: 'rgba(60,200,60,0.8)',
                    borderwidth: 2,
                    fontSize: 10,
                    fontStyle: 'bold',
                    fontColor: '#333',
                    xPadding: 10,
                    yPadding: 10,
                    cornerRadius: 3,
                    position: 'left',
                    xAdjust: 0,
                    yAdjust: 0,
                    enabled: true,
                    content: '平均気温(1919) 13.7℃'
                }
            }
        ]
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