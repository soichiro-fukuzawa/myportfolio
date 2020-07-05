<?php
session_start();
$error_message = "";
if(isset($_POST["name"])){
    include "db_path.php";
    $sql = "SELECT * FROM users WHERE user_name = :name";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
    $sth->execute();

    $userArray = $sth->fetchAll(PDO::FETCH_ASSOC);
    //print_r($userArray);
    if(count($userArray) > 0){
        //IDが存在する
        if($userArray[0]["user_pass"] == $_POST["password"]){
            //パスワードが合っている
            $_SESSION["name"] = $userArray[0]["user_name"];
            $_SESSION["user_id"] = $userArray[0]["user_id"];                                             
            header("Location:category.php");
        }
        else{
            //パスワードが間違っている
            $error_message = "IDまたはパスワードが間違っています。<br>もう一度入力してください";
        }
    }
    else{
        //IDが存在しない
        $error_message = "IDまたはパスワードが間違っています。<br>もう一度入力して下さい";
    }
}

?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.4.1.min.js"></script>
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
    </script>
</head>
<body>
    <?php
    include "ec_header.php";
    ?>
    <h1>ログイン</h1>
    <form action="index.php" method="post">
        <input type="text" name="name" required><br>
        <input type="password" name="password" required><br>
        <input type="submit" value="ログイン">
    </form>
    <p><a href="regist.php">新規登録</a></p>
    <p><a href="#">管理人</a></p>
    <div><?php print $error_message; ?></div>
</body>
</html>
