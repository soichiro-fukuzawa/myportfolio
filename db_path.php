<?php
try{
    $dbh = new PDO("pgsql:host=ec2-34-233-226-84.compute-1.amazonaws.com;port=5432;dbname=d90rkgo8o06k76;user=vnnylgdrblxhof;password=59881c24b1377df395b0b758de86d22e4f8398e98e03f5ed0c7e4dc4ab86dd2b");
}catch(PDOException $e){
    echo "問題が発生した為、ページを表示させることができませんでした。";
}
?>

