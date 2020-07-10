<?php
session_start();
$error_message = "";
//ポストされたusernameがdatabaseに存在するか
if(isset($_POST["name"])){
    include "db_path.php";
    $sql = "SELECT * FROM users WHERE user_name = :name";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
    $sth->execute();

    $userArray = $sth->fetchAll(PDO::FETCH_ASSOC);
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
    <title>ログイン</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript">
    </script>
</head>
<body id="index_body">
    <header class="indexhead">
        <a href="index.php"><img src="logo.png" alt="logo"></a>
    </header>
    <div id="index_box">
    <h1>ログイン</h1>
        <form action="index.php" method="post">
            <p>ユーザーネームとパスワード</p>
            <input type="text" name="name" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="ログイン">
        </form>
        <p>続行することで、<a href="#">利用規約</a> および <a href="#">プライバシー規約</a> に同意するものとみなされます。</p>
    </div>
    <div id="index_box2">
        <p>新しいお客様ですか？</p>
        <button onclick="location.href='regist.php'">アカウント作成はこちら</button>
        <div><?php print $error_message; ?></div>
    </div>
</body>
</html>
