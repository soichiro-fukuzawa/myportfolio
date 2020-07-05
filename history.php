<!DOCTYPE html>
<?php 
//SELECT COUNT(users.user_id) FROM history LEFT JOIN users ON history.user_id = users.user_id LEFT JOIN object ON history.item_id = object.item_id WHERE users.user_id = 1 GROUP BY users.user_id;
//SELECT order_date, history.user_id, user_name, history.item_id, item_name, item_price, image_url, history.sales_quantity FROM history LEFT JOIN users ON history.user_id = users.user_id LEFT JOIN object ON history.item_id = object.item_id WHERE users.user_id = 1;
session_start();
include "login_check.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript">
$(function(){
       $("ul.sub").hide();
       $("ul.menu li").hover(function(){
           $("ul:not(:animated)",this).slideDown("fast");
       },
       function(){
           $("ul",this).slideUp("fast");
       });
});
    </script>
</head>
<body>
<?php
    include "ec_header.php";
    include "db_path.php";
    
    $sql = "SELECT order_date AS 注文日, item_name AS 商品名, item_price AS 価格, history.sales_quantity AS 購入個数, image_url
FROM history 
LEFT JOIN users ON history.user_id = users.user_id 
LEFT JOIN object ON history.item_id = object.item_id 
WHERE users.user_id = :user";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':user', $_SESSION["user_id"], PDO::PARAM_STR);
    $sth->execute();
    $hisArray = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<div>
    <table>
        <?php
        foreach($hisArray as $val){
            echo "<hr>";
            $sum = $val['価格'] * $val['購入個数'];
            echo <<<EOH
            <tr>
                <td>
                    <img src="{$val['image_url']}">
                    <span>購入日{$val['注文日']}</span>
                    <span>商品名{$val['商品名']}</span>
                    <span>価格{$val['価格']}</span>
                    <span>購入個数{$val['購入個数']}</span>
                    <span>購入時合計額{$sum}</span>
                </td>
            </tr>
EOH;
        }
        ?>
    </table>
</div>
</body>
</html>
