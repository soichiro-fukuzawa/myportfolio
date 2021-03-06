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
            $inc = count($userArray) + 1;
            $sql = "INSERT INTO users VALUES (:inc,:name,:pass)";
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':inc', $inc, PDO::PARAM_STR);
            $sth->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
            $sth->bindParam(':pass', $_POST["password"], PDO::PARAM_STR);
            $sth->execute();
            $error_message = "登録が完了いたしました。";
        }
        //パスワードとパスワード２が一致しない
        else{
            $error_message = "パスワードが一致しませんでした。";
        }
    }
    //ポストされたnameが既に存在していた
    else{
        $error_message = "入力されたユーザーネームは既に使われています。";
    }
}
            
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>アカウント作成</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript">
    </script>
</head>
<body id="index_body">
    <header class="indexhead">
        <a href="index.php"><img src="logo.png" alt="logo"></a>
    </header>
    <div id="regist_box">
        <h1>アカウント作成</h1>
        <div><?php print $error_message; ?></div>
        <form action="regist.php" method="post">
            <span>名前</span><input type="text" name="name" required><br>
            <span>パスワード</span><input type="password" name="password" required><br>
            <span>もう一度パスワードを入力してください</span><input type="password" name="password2" required><br>
            <input type="submit" value="アカウントを作成">
        </form>
        <p>ログインすることで、<a href="#">利用規約</a> および <a href="#">プライバシー規約</a> に同意するものとみなされます。</p>
        <p><span>既にアカウントをお持ちですか？→</span><a href="index.php">ログイン</a></p>
    </div>
</body>
</html>
