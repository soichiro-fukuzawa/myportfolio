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
if(isset($_POST["chenge_definition"])){
    $key = $_POST["chenge_suryo"];
    $_SESSION["suryo"][$key] = $_POST["chenge_definition"];
}
if(isset($_POST["delete"])){
    $key = $_POST["delete"];
    unset($_SESSION["item_id"][$key]);
    $_SESSION["item_id"] = array_values($_SESSION["item_id"]);
    unset($_SESSION["価格"][$key]);
    $_SESSION["価格"] = array_values($_SESSION["価格"]);
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
<body id="cart_body">
<?php
include "ec_header.php";
?>
<div id="cart_body_box">
<?php
if(count($_SESSION["item_id"]) >= 1){
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
                <div><img src="{$item["image_url"]}"></div>
                <div class="item_joho">
                    <span class="syohinmei">{$item["商品名"]}</span><br>
                    <span class="redmoji kakaku">￥{$item["価格"]}</span>
                    <form method="post">
EOH;
                if(!isset($_POST["chenge"]) || isset($_POST["chenge_definition"])){
                    echo <<<EOH
                        <span>{$_SESSION["suryo"][$i]}個</span>
                        <input type="hidden" name="chenge" value="{$i}">
                        <input type="submit" value="数量変更">
EOH;
                }
                else{
                    echo "<p class='nokaigyo'>数量:</p>"."<select name='chenge_definition'>";
                    for($a = 1; $a <= $item['数量']; $a++){
                        echo "<option value='{$a}'>{$a}</option>";
                    }
                    echo <<<EOH
                    </select>
                        <input type="hidden" name="chenge_suryo" value="{$i}">
                        <input type="submit" value="確定">
EOH;
                }
                echo <<<EOH
                </form>
                <form method="post">
                    <input type="hidden" name="delete" value="{$i}">
                    <input type="submit" value="削除">
                </form>
            </div>
        </div>
EOH;
            }
        }
    }
    echo "</div>";
}
?>
<?php
if(count($_SESSION["価格"]) >= 1){
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