<!DOCTYPE html>
<html>
<body>

<?php
    echo "Saving"."<br>";
    //    var_dump($_GET);
    echo "Presentation ".$_GET["presentationName"]."<br>";
    echo "Slide ".$_GET["slide"]."<br>";
    echo "Content ".$_GET["content"]."<br>";
    
    // load $_GET["presentation"], $_GET["slide"]
?>

    <form id="foo" method="get" action="editor.php">
        <input type="hidden" name="content" value=<?php echo "\"".$_GET["content"]."\"" ?> />
        <input type="hidden" name="presentationName" value=<?php echo $_GET["presentationName"] ?> />
        <input type="hidden" name="slide" value= <?php echo $_GET["slide"] ?> />
        <input type="submit" value="Back"/>
    </form>


</body>
</html>
