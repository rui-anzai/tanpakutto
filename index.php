<?php
  include 'conn.php';
  include 'insert.php';
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <link rel="stylesheet" href="example.css">
        <meta charset="utf-8">
        <title>タンパクっと</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.min.js'></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    </head>
    <body>

    <header class="fixed-top" style="background-color:black;">
      <!--  <h1 class="text-center">たんぱくっと</h1>-->
       <p><img src="kinnikukakudai2.png" alt=""width="75" height="85"style=" text-align: center;"class="kinniku"></p>
    </header>
      <br></br> 
     <br></br>
      <br></br>
 <h2 style="margin-top:50px;">本日は<?php echo (int)$goukei; ?>グラム摂取しています</h2>
      <br></br>
     

<div class="container">
  <div class="row">
    <div class="col-6"><!--左半分-->
    

<table border="1" style="border-collapse: collapse;">
  <thead style="display: block;">
    <tr>
      <th style="width:180px;border:1px solid;">食品</th>
      <th style="width: 180px;border:1px solid;">タンパク質</th>
      <th style="width:180px;border:1px solid;">選択数</th>
    </tr>
  </thead>

  <tbody style="display: block;overflow-y:scroll;height:400px;">
    <?php
//配列$products
foreach($products as $p){
$id = $p['id'];
$name = $p['food_name'];
$protein = $p['protein'];
$order = $p['order_quantity'];
echo "<tr><td style='width:180px;'><a href='product.php?id={$id}'>{$name}</a></td><td style='width:180px;'>{$protein}グラム</td><td style='width:180px;'>{$order}個</td></tr>";
}
?>
   
  </tbody>
</table>
 </div><!--左半分のcolの閉じたぐ-->
    <div class="col-6"><!--右半分のcolの閉じたぐ-->
    
<div class="sousa">
 <form action="index.php" method="post">

  <input type="image" src="touroku.png" class="btn-social-circle btn-social-circle--hatebu">
  <input type="image" src="gomi.png" class="btn-social-circle btn-social-circle--pocket">
  <input type="image" src="reload.png" class="btn-social-circle btn-social-circle--feedly">
     </form> 
     </div>
 
<!-- ドーナツチャート読み込み -->
<canvas id="myPieChart"></canvas>
<div id = "pie-chart"></div>

        </div><!--右半分のcolの閉じたぐ-->
</div><!--rowの閉じたぐ-->

<!--本日のノルマは完了しています-->
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
            //
            $db->exec("UPDATE `food_products` SET `order_quantity`=0 WHERE 1"); //注文数を0にリセット
            //$db->exec("SELECT * FROM `chart` WHERE date_time = (SELECT MAX(date_time) FROM chart)");
            //$db->exec("UPDATE `chart` SET `total`= 0 WHERE 1");
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

     <script>
      var total_protein = <?php echo (int)$goukei ?>;
      function pie(){
        if(total_protein <= 65){
        var value = `本日は残り${65 - total_protein}グラムです`;
        }else{
          var value = "本日のノルマは完了しています"
        }
        document.getElementById('pie-chart').innerHTML = value ;
      }
      pie();

      var blue = '#FFFF00';
      var gray = 'rgb(99, 99, 99)';
      var ctx = document.getElementById("myPieChart");
      var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          datasets: [{
              backgroundColor: [blue, gray],
              data: [total_protein,65-total_protein]
          }]
        },
        options: {
          title: {
            
          }
        }
      });
      </script>
      
<div class="row">
    <div class="col-12">
       <br></br>
     <h2 >直近一週間のグラフ</h2> 
   
 
         <!--棒グラフの表示-->  
 <div class="bar">
   
      <div class="chart-container">
          <canvas class="text-center" id="myLineChart" style="margin-bottom:50px;">ここにチャート表示</canvas>
     </div> 
</div>
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
      chartArea: {
        backgroundColor: 'white'
       },
      scales: {
        xAxes: [{
          id : 'x軸',
          ticks: {
            autoSkip: true,
            maxTicksLimit: 7  // 最大表示数
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
                scaleID: 'y軸', // 基準とする軸のid名
                value: 65.0, // 引きたい線の数値（始点）
                endValue: 65.0, // 引きたい線の数値（終点）
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
                    content: '1日の目標摂取量[タンパク質]'
                }
            },

        ]
    }
        }
  }); 
$(function() {
	setTimeout(function(){
		$('.start p').fadeIn(1600);
	},500); //0.5秒後にロゴをフェードイン!
	setTimeout(function(){
		$('.start').fadeOut(500);
	},2500); //2.5秒後にロゴ含め真っ白背景をフェードアウト！
});
/*
  // グラフオプションの title 指定を削除しただけです
(function() {
  var blue = 'rgb(54, 162, 235)';
  var gray = 'rgb(99, 99, 99)';

  //円グラフの中身の割合
  var data = {
    datasets: [{
      data: [total_protein,65-total_protein],
      backgroundColor: [blue, gray],
    }],
  };

// 文字列に変換
  //var dataString = dataset.data[index].toString();

  // 文字の配置（ "0" のときは配置しない）
  // if( dataString!=="0" ) {
  //   ctx.textAlign = 'center';
  //   ctx.textBaseline = 'middle';
  //   var padding = 5;
  //   var position = element.tooltipPosition();
  //   ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
  // }


  // グラフオプション
  var options = {
    // グラフの太さ（中央部分を何％切り取るか）
    cutoutPercentage: 65,
    // 凡例を表示しない
    legend: { display: false },
    // 自動サイズ変更をしない
    responsive: false,
    title: {
      display: true,
      fontSize: 16,
      text: 'baka',
    },
    // マウスオーバー時に情報を表示しない
    tooltips: { enabled: true },
  };




  // グラフ描画
  var ctx = document.getElementById('chart-area').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: data,
    options: options
  });
})();

var chartJsPluginCenterLabel = {
  labelShown: false,

  afterRender: function (chart) {
    // afterRender は何度も実行されるので、２回目以降は処理しない
    if (this.labelShown) {
      return;
    }
    this.labelShown = true;
    // ラベルの HTML
    //数値挿入

    /*if(x <= 65){
        console.log(`本日は残り${65 - x}グラムです`);
    }else{
        console.log("本日のノルマは達成されています")
    }


    //円グラフの中の条件分岐
    if(total_protein <= 65){
      var value = `本日は残り${65 - total_protein}グラムです`;
    }else{
      var value = "本日のノルマは完了しています"
    }

    var labelBox = document.createElement('div');
    labelBox.classList.add('label-box');
    labelBox.innerHTML = '<div class="label">'
      + value
      + '<span class="per">%</span>'
      + '</div>';
};

// 上記プラグインの有効化
Chart.plugins.register(chartJsPluginCenterLabel);*/

      </script>
  <!--  <form action="index.php" method="post">
        <button type="submit" name="add">登録</button>
        <button type="submit" name="update">更新</button>
        <button type="submit" name="remove">削除</button>
      </form>    
      -->    
    <!--棒グラフのスクリプト-->
    
  </div><!--containarの閉じたぐ-->
     <div class="start">
    <p><img src="kinniku3.png" alt=""width="400" height="400"></p>
    </div>
    <footer class="fixed-bottom" style="background-color:black;">
        <p class="text-center" style="color:white; ">&copy;2021 大腿四頭筋</p>
    </footer>
   
 </body>
</html> 
 