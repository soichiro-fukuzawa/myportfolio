<?php
session_start();
$error_message = "";
if(isset($_POST["id"])){
    include "db_path.php";
    $sql = "SELECT * FROM user WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':id', $_POST["id"], PDO::PARAM_STR);
    $sth->execute();

    $userArray = $sth->fetchAll(PDO::FETCH_ASSOC);

    if(count($userArray) > 0){
        //IDが存在する
        if($userArray[0]["password"] == $_POST["password"]){
            //パスワードが合っている
            $_SESSION["name"] = $userArray[0]["name"];
            $_SESSION["id"] = $userArray[0]["id"];                                             
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
    <link rel="stylesheet" href="style.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript"></script>
</head>
<body>
<?php
include "ec_header.php";
?>
    <h1>ログイン</h1>
    <p>id</p>
    <form action="index.php" method="post">
        <input type="text" name="id"><br>
        <input type="password" name="password"><br>
        <input type="submit" value="ログイン">
    </form>
    <div><?php print $error_message; ?></div>
<?php
include "ec_footer.php";
?>
</body>
</html>

