<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body> 
<?php
//接続
$dsn =データベース名;
	$user = ユーザー名;
	$password = パスワード;
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS board"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "password TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
//新規投稿
if(isset($_POST["text"])){
    if(isset($_POST["name"])){
        if(!empty($_POST["text"])){    
            if(!empty($_POST["name"])){
                if($_POST["password"]==514){
                    if(empty($_POST["editdecide"])){ 
                        //受け取りデータの変数の指定
                             $comment= $_POST["text"];
                             $name=$_POST["name"];
                             $date = date("Y年m月d日 H時i分s秒");
                             $password=$_POST["password"];
                        //テーブルのデータ？
                        $sql = $pdo -> prepare("INSERT INTO board (name, comment,date,password) VALUES (:name, :comment, :date, :password)");
                        	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
                        	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
                        	$sql -> execute();
                    }
                }else{
                    echo "パスワードが間違っています<br>";
            }}else{
                echo "名前を入力してください<br>";
        }}else{
            echo "コメントを入力してください<br>";
        }
    }
}
 

//削除機能
if(isset($_POST["deletenumber"])){
if(!empty($_POST["deletenumber"])){
    if($_POST["password2"]==514){
        $id=$_POST["deletenumber"];
        $sql = 'SELECT * FROM board';
    	$stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
            if($row["id"]==$id){
                $sql = 'delete from board where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }else{
     echo "パスワードが間違っています";
}}else{
   echo "削除番号を入力してください";
}
}
//編集機能
if(!empty($_POST["editdecide"])&&$_POST["password"]==514){
    $id=$_POST["editdecide"];
    $comment= $_POST["text"];
     $name=$_POST["name"];
     $date = date("Y年m月d日 H時i分s秒");
     $password=$_POST["password"];
      $sql = 'SELECT * FROM board';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
        if($row["id"]==$id){
            $sql = 'UPDATE board SET name=:name,comment=:comment WHERE id=:id';
        	$stmt = $pdo->prepare($sql);
        	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
        	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        	$stmt->execute();
        }
	}
}

//編集内容の自動入力機能
if(isset($_POST["editnumber"])){
if(!empty($_POST["editnumber"])){
    if($_POST["password3"]==514){
        $id=$_POST["editnumber"];
          $sql = 'SELECT * FROM board';
    	$stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    	   if($row['id'] == $id) { 
    	    $editname=$row["name"];
    	    $editcomment=$row["comment"];
    	   }
	    }
    }else{
        echo "パスワードが間違っています<br>";
}}else{
    echo "編集番号を入力してください";
}
}
?>


<form action="" method="post">
        名前：<br>
        <input type="text" name="name"value="<?php echo $editname;?>"><br>
        コメント：<br>
        <input type="text" name="text"value="<?php echo $editcomment;?>"><br>
         Password:<br>
        <input type="text" name="password"><br>
        <input hidden="number" name="editdecide"value="<?php if($_POST["password3"]==514){echo $_POST["editnumber"];}?>"><br>
        <input type="submit"value="送信"><br>
    </form>
<form action="" method="post">
    削除番号:<br>
    <input type="number" name="deletenumber"><br>
    Password:<br>
    <input type="number" name="password2"><br>
    <input type="submit"value="送信"><br>
    </form>
<form action="" method="post">
    編集番号：<br>
    <input type="number" name="editnumber"><br>
    Password：<br>
        <input type="number" name="password3"><br>
        <input type="submit" name="edit"><br>
    </form>
<?php 
//表示
$sql = 'SELECT * FROM board';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
?>
</body>
</html>