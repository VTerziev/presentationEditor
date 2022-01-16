<!DOCTYPE html>
<html>
<body>

<?php
    echo "Previewing"."<br>";
    echo "Presentation: ".$_GET["presentationName"]."<br>";
    echo "Slide: ".$_GET["slide"]."<br>";

    // 1. Write the received SLIM code in a file
    $myfile = fopen("web-slides/lectures/contentForPreview.slim", "rw") or die("Unable to open file!");
    fwrite($myfile, $_GET["content"]);
    fclose($myfile);

    shell_exec("rm web-slides/html/contentForPreview.html");
    // 2. Run the HTML generation described in https://github.com/IliaSky/web-slides
    echo shell_exec("cd web-slides/ && node presentations.js contentForPreview"); // doesn't work, when executed this way 
?>
    <form id="foo" method="get" action="editor.php">
        <!-- <input type="hidden" name="content" value=<?php echo "\"".$_GET["content"]."\"" ?> /> // I couldn't escape the "content" properly --> 
        <input type="hidden" name="presentationName" value=<?php echo $_GET["presentationName"] ?> />
        <input type="hidden" name="slide" value= <?php echo $_GET["slide"] ?> />
        <input type="submit" value="Edit"/>
    </form>

    <form id="foo" method="get" action="save.php">
        <!-- <input type="hidden" name="content" value=<?php echo "\"".$_GET["content"]."\"" ?> /> -->
        <input type="hidden" name="presentationName" value=<?php echo $_GET["presentationName"] ?> />
        <input type="hidden" name="slide" value= <?php echo $_GET["slide"] ?> />
        <input type="submit" value="Save"/>
    </form>

    <!-- 3. Visualise the produced HTML -->
    <embed type="text/html" src="web-slides/html/contentForPreview.html#2" width="500" height="500">

</body>
</html>
