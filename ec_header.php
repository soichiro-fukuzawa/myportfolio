<?php
if(isset($_SESSION["item_id"])){
    $massege = count($_SESSION["item_id"]);
}
else{
    $massege = "0";
}
print <<<EOH
<header>
    <div>
        <ul class="menu">
            <li id="logo"><a href="category.php"><img src="amozon.png"></a></li>
            <li id="sofa" class="cate_li">ソファ</li>
            <li id="table" class="cate_li">テーブル</li>
            <li id="chair" class="cate_li">椅子</li>
            <li id="denki" class="cate_li">電化製品</li>
EOH;
if(isset($_SESSION["name"])){
    print <<<EOH
        <li class="menu_main">
            <a href="#">{$_SESSION["name"]}さん▼</a>
            <ul class="sub">
                <li><a href="history.php">購入履歴</a></li>
                <li><a href="logout.php">ログアウト</a></li>
            </ul>
        </li>
EOH;
}
else{
    print "<li><a href='index.php'>ログイン</a></li>";
}
print <<<EOF
            <li><a href="cart.php">カート:{$massege}</a></li>
        </ul>
    </div>
</header>
EOF;
?>

