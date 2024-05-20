<!DOCTYPE html>
<html>
    <head>
        <title>Profilo</title>
    </head>
    <body>

        <nav>
            <a href="homepage"> Homepage</a>
            <br> 
            <a href="login">Login</a>
            <br>
            <a href="biglietti">biglietti</a>
            <br>
            <a href="profilo">Profilo</a>
            <br>
            <a href="eventi">Eventi</a>
            <br>
            <a href="aboutUs">About Us</a>
            <br>
        </nav>

        <br>

        <h1>Profilo</h1>

        <p>Username: <?php echo $user -> username;?> </p>
        <br>

        <?php 
            foreach ($biglietti as $biglietto) {
                echo "id".$biglietto -> id;
                //ci si mette altro l'ideale sarebbe una card che descrive il biglietto
            }
        ?>
        

    </body>
</html>