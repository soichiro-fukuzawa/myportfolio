<?php
session_start();
$error_message = "";
$cnt = 0;
include "db_path.php";
$sql = "SELECT * FROM users ORDER BY user_id ASC";
$sth = $dbh->prepare($sql);
$sth->execute();
$userArray = $sth->fetchAll(PDO::FETCH_ASSOC);
foreach($userArray as $users){
    echo $users["user_name"];
    //入力されたユーザーnameが存在する
    if(isset($_POST["name"])){
        if($_POST["name"] == $users["user_name"]){
            $cnt++;
        }
    }
}
if(isset($_POST["name"])){
    if($cnt == 0){
        //パスワードとパスワード２が一致した場合
        if($_POST["password"] == $_POST["password2"]){
            $inc = count($users) + 1;
            $sql = "INSERT INTO users VALUES (:inc,:name,:pass)";
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':inc', $inc, PDO::PARAM_STR);
            $sth->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
            $sth->bindParam(':pass', $_POST["password"], PDO::PARAM_STR);
            $sth->execute();
            $error_message = "登録が完了いたしました。";
        }
        else{
            $error_message = "パスワードが一致しませんでした。";
        }
    }
    else{
        $error_message = "入力されたユーザーネームは既に使われています。";
    }
}
else{
    $error_message = "ユーザー情報を入力してください。";
}
            
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
    <header>
        <p>新規登録ページ</p>
    </header>
    <h1>新規登録</h1>
    <form action="regist.php" method="post">
        名前：<input type="text" name="name" required><br>
        パスワード：<input type="password" name="password" required><br>
        パスワード２：<input type="password" name="password2" required><br>
        <input type="submit" value="登録">
    </form>
    <p><a href="index.php">ログイン</a></p>
    <div><?php print $error_message; ?></div>
</body>
</html>
