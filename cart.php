<?php
session_start();
include "login_check.php";
if(isset($_POST["cart_check"])){
    $a = 0;
    foreach($_POST as $key => $val){
        $item_id[$a] = $key;
        $suryo_cate[$a] = $val;
        $a++;
    }
    if(!isset($_SESSION["item_id"])){
        $_SESSION["item_id"][0] = $item_id[0];
        $_SESSION["suryo"][0] = $suryo_cate[0];
    }
    else{
        $max = count($_SESSION["item_id"]);
        $_SESSION["item_id"][$max] = $item_id[0];
        $max2 = count($_SESSION["suryo"]);
        $_SESSION["suryo"][$max2] = $suryo_cate[0];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>カート</title>
<link rel="stylesheet" href="style.css">
<script src="jquery-3.4.1.min.js"></script>
<script type="text/javascript">
/*$(function(){
    var cartArray = $("span:ntr-child(2)").html();
    $('tr').each(function(){ 
        cartArray.push();
    });
    var total = 0;
    for (var i = 0; i < someArray.length; i++) {
        total += someArray[i] << 0;
    }
    $("#gokei").html(total);
    $("span:ntr-child(2)").html();
    $('table').on('click', 'button', function(e){
       $(this).closest('tr').remove();
    });
});*/
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
<body id="cart_body">
<?php
include "ec_header.php";
?>
<div id="cart_body_box">
<?php
if(isset($_SESSION["item_id"])){
    echo <<<EOH
    <h1>ショッピングカート</h1>
    <div id="cart_box">
EOH;
    include "db_path.php";
    $sql = "SELECT item_id, item_name AS 商品名, item_price AS 価格, quantity AS 数量, image_url FROM object ORDER BY item_id ASC";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $itemArray = $sth->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < count($_SESSION["item_id"]); $i++){
        foreach($itemArray as $item){
            if($item["item_id"] == $_SESSION["item_id"][$i]){
                $_SESSION["価格"][$i] = $item["価格"] * $_SESSION["suryo"][$i];
                echo <<<EOH
            <div class="hako">
                <span><img src="{$item["image_url"]}"></span>
                <span class="syohinmei">{$item["商品名"]}</span>
                <span class="redmoji">￥{$item["価格"]}</span>
                <span>{$_SESSION["suryo"][$i]}個</span>
                <!--<span><button>削除</button></span>-->
            </div>
EOH;
            }
        }
    }
    echo "</div>";
}
?>
<?php
if(isset($_SESSION["価格"])){
    $kosu = count($_SESSION["item_id"]);
    $gokei_messagi = array_sum($_SESSION["価格"]);
    echo <<<EOH
    <div id="cart_box2">
        <a href="category.php">お買い物を続ける</a>
        <p id="gokei">小計({$kosu}個)：<span class="redmoji">￥{$gokei_messagi}</span></p>
        <form action="konyu_kakunin.php" method="post">
            <input type="submit" name="konyu" value="購入">
        </form>
    </div>
EOH;
}
else{
    $gokei_messagi = "カートは空です。";
    echo <<<EOH
    <div id="cart_box3">
        <a href="category.php">お買い物をする</a>
        <p id="gokei">{$gokei_messagi}</p>
    </div>
EOH;
}
?>
</div>
</body>
</html>