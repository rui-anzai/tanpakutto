<?php
  include 'db_config.php';
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