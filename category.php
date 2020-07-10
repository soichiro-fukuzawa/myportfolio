<!DOCTYPE html>
<?php 
session_start();
include "login_check.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>カテゴリー</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript">
$(function(){
    $(".box").click(function(){
        $("<form>",{id:"itemselect",action:"syohin.php",method:"post"})
        .append($("<input>",{type:"hidden",name:"cate",value: $(this).attr("id") }))
        .appendTo(document.body)
        .submit();
    });
});
$(function(){
    $(".cate_li").click(function(){
        $("<form>",{id:"itemselect",action:"syohin.php",method:"post"})
        .append($("<input>",{type:"hidden",name:"cate",value: $(this).attr("id") }))
        .appendTo(document.body)
        .submit();
    });
});
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
    //databaseのカテゴリーテーブルをカテゴリーidの昇順で配列へ入れる
    $sql = "SELECT * FROM category ORDER BY cate_id ASC";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $cateArray = $sth->fetchAll(PDO::FETCH_ASSOC);
    //オブジェクトテーブル(商品のデータが入っている)をカテゴリーidの昇順で配列へ入れる
    $sql = "SELECT cate_id, image_url FROM object ORDER BY cate_id ASC;";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $obArray = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo "<div id='sotowaku_cate'>";
    $a = 0;
    foreach($cateArray as $user){
        echo <<<EOH
        <div id="{$user['cate_class']}" class="box">
            <figure class="fig_cate">
                <figcaption>{$user['cate_name']}</figcaption>
                <div>
EOH;
        //商品のidの1～3の3つカテゴリーの例として画像を表示
        for($i = 0; $i < 3; $i++){
            echo "<img src='{$obArray[$a]["image_url"]}'>";
            $a++;
        }
        echo <<<EOH
                </div>
            </figure>
        </div>
EOH;
    }
    echo "</div>";
    ?>
</body>
</html>
