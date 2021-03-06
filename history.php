<!DOCTYPE html>
<?php 
session_start();
include "login_check.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>購入履歴</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript">
/*ドロップダウンメニュー*/
$(function(){
       $("ul.sub").hide();
       $("ul.menu li").hover(function(){
           $("ul:not(:animated)",this).slideDown("fast");
       },
       function(){
           $("ul",this).slideUp("fast");
       });
});
/*headerにあるカテゴリーからカテゴリーIDをsyohin.phpへ飛ばし、カテゴリーIDの商品を表示*/
$(function(){
    $(".cate_li").click(function(){
        $("<form>",{id:"itemselect",action:"syohin.php",method:"post"})
        .append($("<input>",{type:"hidden",name:"cate",value: $(this).attr("id") }))
        .appendTo(document.body)
        .submit();
    });
});
    </script>
</head>
<body class="bowh">
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
    <h1 class="h1_mar">購入履歴</h1>
    <div id="history_box">
        <?php
        //category.phpもしくはheaderのカテゴリーから選択された商品一覧を表示
        foreach($hisArray as $val){
            $sum = $val['価格'] * $val['購入個数'];
            $ymd = date('Y年m月d日',strtotime($val['注文日']));
            echo <<<EOH
            <div class="his_hako">
                    <span class="his_img"><img src="{$val['image_url']}"></span>
                    <span>購入日:{$ymd}</span>
                    <span>商品名:{$val['商品名']}</span><br>
                    <span>価格:￥{$val['価格']}</span>
                    <span>購入数:{$val['購入個数']}</span><br>
                    <span>購入時合計額:{$sum}</span>
            </div>
EOH;
        }
        ?>
    </div>
</body>
</html>
