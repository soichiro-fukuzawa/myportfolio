<?php
session_start();
include "login_check.php";
//var_dump($_POST);
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
/*if(isset($_SESSION["item_id"][0])&&isset($_POST["itemid"])){
    if(!in_array($_POST["itemid"],$_SESSION["item_id"])){
        $max = count($_SESSION["item_id"]);
        $_SESSION["item_id"][$max] = $_POST["itemid"];
        $_SESSION["kosu"][$max] = 1;
    }
}
elseif(isset($_POST["itemid"])){
    $_SESSION["item_id"] = array($_POST["itemid"]);
    $_SESSION["kosu"] = array(1);
}*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title></title>
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
<body>
<?php
include "ec_header.php";
?>
<div>
    <table>
<?php
if(isset($_SESSION["item_id"])){
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
                <tr>
                    <td>
                        <img src="{$item["image_url"]}">
                        <span>{$item["商品名"]}</span>
                        <span>{$item["価格"]}円</span>
                        <span>{$_SESSION["suryo"][$i]}個</span>
                        <button>削除</button>
                    </td>
                </tr>
EOH;
            }
        }
    }
}
if(isset($_SESSION["価格"])){
    $gokei_messagi = array_sum($_SESSION["価格"]);
    echo <<<EOH
    </table>
    <div>
        <p id="gokei">合計:{$gokei_messagi}円</p>
        <form action="konyu_kakunin.php" method="post">
            <input type="submit" name="konyu" value="購入">
        </form>
        <a href="category.php">戻る</a>
    </div>
EOH;
}
else{
    $gokei_messagi = "カートが空です。";
    echo <<<EOH
    </table>
    <div>
        <p id="gokei">{$gokei_messagi}</p>
        <a href="category.php">戻る</a>
    </div>
EOH;
}
   
?>
</div>
</body>
</html>
<!--/* echo "<div id='sotowaku'>";
    $a = 0;
    foreach($cateArray as $user){
        echo <<<EOH
        <div id="{$user['cate_class']}" class="box">
            <figure>
                <div>
EOH;
        for($i = 0; $i < 3; $i++){
            echo "<img src='{$obArray[$a]["image_url"]}'>";
            $a++;
        }
        echo <<<EOH
                </div>
                <figcaption>{$user['cate_name']}</figcaption>
            </figure>
        </div>
EOH;
}
    echo "</div>";*/
/*if(isset( $_SESSION["item_id"])){
print <<<EOH
    <form method="post" action="konyu_kakunin.php">
EOH;
    include "db_path.php";
    $count = 0;
    for($i=0;$i<count($_SESSION["item_id"]);$i++){
        $sql = "SELECT * FROM object where id = :id";
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':id', $_SESSION["item_id"][$i], PDO::PARAM_STR);
        $sth->execute();
        $itemarray = $sth->fetchAll(PDO::FETCH_ASSOC);
        $ketakugiri = number_format($itemarray[0]["value"]);
        print <<<EOH
        <img src="images/{$itemarray[0]["ob_img"]}" alt="{$itemarray[0]["name"]}">
        <p>{$itemarray[0]["name"]}</p>
        <input type="text" name="kosu{$i}" value="{$_SESSION['kosu'][$i]}" size="1">
        <p>￥{$ketakugiri}</p>
        <input type="hidden" name="kakaku{$i}" value="{$itemarray[0]["value"]}">
EOH;
        $count++;
    }
print <<<EOH
        <input type="hidden" name="syurui_kazu" value="{$count}">
        <input type="submit" name="suryohenko" value="数量変更">
        <p>購入しますか?</p>
        <input type="submit" name="konyu" value="レジへ">
    </form>
EOH;
}
else{
    print "カートが空です。";
}*/-->
