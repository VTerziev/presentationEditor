<!DOCTYPE html>
<html>
<body>

<?php
    // foreach(PDO::getAvailableDrivers() as $driver) {
    //     echo $driver.'<br />';
    // }

    $mysql_host = "localhost";
    $mysql_database = "database";
    $mysql_user = "root";
    $mysql_password = "";

    try {
        $dbh = new PDO("mysql:host=$mysql_host;$mysql_database",
        $mysql_user, $mysql_password);
        echo 'Connected to database'.'<br/>';
    } catch(PDOException $e) {
        echo 'failed!';
        echo $e->getMessage();
    }

    // $sql = "SELECT * FROM page_hits";
    // foreach ($dbh->query($sql) as $row) {
    //     echo $row['page'] .' - '. $row['visited'] . '<br />';
    // }

    $dbh = null;


    echo "Editing"."<br>";
    //    var_dump($_GET);
    if (is_null($_GET["slide"]) ) {
        $_GET["slide"] = 0;
    }
    
    echo "Presentation ".$_GET["presentationName"]."<br>";
    echo "Slide ".$_GET["slide"]."<br>";
    echo "Content ".$_GET["content"]."<br>"; // TODO load that from DB 
    
    // load $_GET["presentation"], $_GET["slide"]
?>

    <form id="foo" method="get" action="editor.php">
        <input type="hidden" name="presentationName" value=<?php echo $_GET["presentationName"] ?> />
        <input type="hidden" name="slide" value= <?php echo ($_GET["slide"]-1) ?> />
        <input type="submit" value="Previous Slide" <?php if($_GET["slide"]==0){echo "disabled";}?>/>
    </form>

    <form id="foo" method="get" action="editor.php">
        <input type="hidden" name="presentationName" value=<?php echo $_GET["presentationName"] ?> />
        <input type="hidden" name="slide" value= <?php echo ($_GET["slide"]+1) ?> />
        <input type="submit" value="Next Slide"/>
    </form>

    <form id="foo" method="get" action="preview.php">

        <textarea rows = "15" cols = "60" name = "content">
<?php echo $_GET["content"] ?>
         </textarea><br>

    <!-- <input type="text" name="content" value=<?php echo "\"".$_GET["content"]."\"" ?> /> -->


        <input type="hidden" name="presentationName" value=<?php echo $_GET["presentationName"] ?> />
        <input type="hidden" name="slide" value= <?php echo $_GET["slide"] ?> />
        <input type="submit" value="Preview"/>
    </form>

</body>
</html>
