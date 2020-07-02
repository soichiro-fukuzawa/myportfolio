<?php
print <<<EOH
<header>
    <div id="head_menu">
            <ul>
                <li id="side_menu"><span>―</span></li>
                <li><a href="index.php"><img src="images/amozon.png" alt="rogo"></a></li>
EOH;
                if(isset($_SESSION["name"])){
                    print "<li class='button'>".$_SESSION["name"]."さん</li>";
                }
                else{
                    print "<li class='button'><a href='index.php'>ログイン</a></li>";
                }
print <<<EOF
                <li class="button"><a href="cart.php">カート</a></li>
            </ul>
    </div>
</header>
EOF;
?>

