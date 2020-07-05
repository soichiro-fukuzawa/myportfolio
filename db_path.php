<?php
try{
    $dbh = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=10063603");
}catch(PDOException $e){
    echo "データベースとの接続に失敗しました。";
}
?>

