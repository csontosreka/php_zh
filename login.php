<html>
    <head>
        <title>Favourite color</title>
    </head>
    <body>
        <?php
            echo '<form action ="processing.php" method="POST">
                    Username: <br>
                    <input type="text" name="u_name"> <br>
                    Password: <br>
                    <input type ="password" name="pwd"><br>
                    <input type="submit" value="Sign in">
                </form>';
        ?>
    </body>
</html>