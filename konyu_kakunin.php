<?php
session_start();
include "login_check.php";
//cart.phpで購入が押されてきた
if(isset($_POST["konyu"])){
    $gokei = array_sum($_SESSION["価格"]);
    $gokei_ketakugiri = number_format($gokei);
    $konyu_kakunin = <<<EOH
        <div class="konyu">
            <form method="post">
                <p>合計金額<span class="redmoji">￥{$gokei_ketakugiri}</span></p>
                <p>購入を確定しますか？</p>
                <input type="submit" name="kakutei" value="購入確定">
                <input type="submit" name="carthemodoru" value="カートへ戻る">
            </form>
        </div>
EOH;
}
//このページで購入確定が押された
elseif(isset($_POST["kakutei"])){
    include "db_path.php";
    $sql = "SELECT item_id, quantity, sales_quantity FROM object ORDER BY item_id ASC";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $itemArray = $sth->fetchAll(PDO::FETCH_ASSOC);
    //カートに入っている商品の数分、履歴にインサートして更新する
    for($i = 0; $i < count($_SESSION["item_id"]); $i++){
        //INSERT
        $sql = "INSERT INTO history VALUES (NOW(),:user,:item,:sales)";
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':user', $_SESSION["user_id"], PDO::PARAM_STR);
        $sth->bindParam(':item', $_SESSION["item_id"][$i], PDO::PARAM_STR);
        $sth->bindParam(':sales', $_SESSION["suryo"][$i], PDO::PARAM_STR);
        $sth->execute();
        //UPDATE
        $key = $_SESSION["item_id"][$i] - 1;
        $zanryo = $itemArray[$key]["quantity"];
        $sales = $zanryo - $_SESSION["suryo"][$i];
        $sql = "UPDATE object SET quantity = :nokori, sales_quantity = :sales WHERE item_id = :item";
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':sales', $_SESSION["suryo"][$i], PDO::PARAM_STR);
        $sth->bindParam(':item', $_SESSION["item_id"][$i], PDO::PARAM_STR);
        $sth->bindParam(':nokori', $sales, PDO::PARAM_STR);
        $sth->execute();
    }
    //一通り終わったらセッション（アイテムid）と（数量）を消す
    unset($_SESSION["価格"]);
    unset($_SESSION["item_id"]);
    unset($_SESSION["suryo"]);
    //購入確定時のhtml表記
    $konyu_kakunin = <<<EOH
            <div class="konyu">
                <p>ご購入ありがとうございます。</p>
                <a href="category.php">カテゴリ一覧へ</a>
            </div>
EOH;
}
//このページでカートへ戻るが押された
elseif(isset($_POST["carthemodoru"])){
    header('Location:cart.php');
}
//それ以外の方法でこのページに来た
else{
    header('Location:category.php');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>購入確定</title>
        <link rel="stylesheet" href="style.css">
        <script src="jquery-3.4.1.min.js"></script>
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
    <body id="konyu_body">
        <?php
        include "ec_header.php";
        print $konyu_kakunin;
        ?>
    </body>
</html>
