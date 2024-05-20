<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
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

        <h1>Login</h1>


        <?php 
            session_start();
            if(isset($_SESSION['user']))
                echo $_SESSION['user']-> loginError;
        ?>
        <form action="elaboraLogin" method="post">


            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="username" required minlength="6">

            <br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="password" required minlength="8">

            <br>

            <input type="submit">

        </form>

        <br>
        <a href = "signin">
            <button >Non sei registrato? Fallo qui!</button>
        </a>
        

    </body>
</html>