<?php
session_start();
if(isset($_POST["cate"])){
    $cate = $_POST["cate"];
    include "db_path.php";
    $sql = "SELECT * FROM category WHERE cate_class = :cate";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':cate', $cate, PDO::PARAM_STR);
    $sth->execute();
    $itemselect = $sth->fetchAll(PDO::FETCH_ASSOC);
}
else{
    header('Location:category.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>商品一覧</title>
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
<body>
<?php
include "ec_header.php";
$sql = "SELECT cate_name AS カテゴリー, item_name AS 商品名, item_price AS 価格,quantity AS 個数, image_url, cate_class, item_id FROM object INNER JOIN category ON (object.cate_id = category.cate_id) WHERE object.cate_id = :select";
$sth = $dbh->prepare($sql);
$sth->bindParam(":select", $itemselect[0]["cate_id"], PDO::PARAM_STR);
$sth->execute();
$itemArray = $sth->fetchAll(PDO::FETCH_ASSOC);

echo "<div id='sotowaku'>";
foreach($itemArray as $kai){
    $ketakugiri = number_format($kai["価格"]);
    echo <<<EOH
    <div class="syohin_box">
        <form action="cart.php" method="post"><!-- 応急処置 -->
            <figure class="syo_fig">
                <img src="{$kai["image_url"]}" alt="{$kai["商品名"]}">
                <figcaption>{$kai["商品名"]}</figcaption>
                <p class="redmoji">￥{$ketakugiri}</p>
EOH;
        if($kai["個数"] == 0){
            echo "<p>在庫切れ</p>";
        }
        else{
            echo "<p class='nokaigyo'>数量:</p>"."<select name=".$kai['item_id'].">";
            $a = 1;
            for($i = 0; $i < $kai["個数"]; $i++){
                echo "<option value='{$a}'>{$a}</option>";
                $a++;
            }
            echo <<<EOH
            </select>
            <input type="hidden" name="cart_check" value="{$itemArray[0]["cate_class"]}">
            <br><input type="submit" value="カートへ追加"><!-- 応急処置 -->
EOH;
        }
        echo <<<EOH
            </figure>
        </form><!-- 応急処置 -->
    </div>
EOH;
}
echo "</div>";
?>
</body>
</html>
