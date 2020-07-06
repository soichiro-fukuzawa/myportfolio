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
EOH;
if(isset($_SESSION["name"])){
    print <<<EOH
    <li class="menu_main">
        <a href="#">{$_SESSION["name"]}</a>
        <ul class="sub">
            <li><a href="history.php">購入履歴</a></li>
            <li><a href="#">メニュー</a></li>
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

