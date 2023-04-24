<?php
session_start();

if(isset($_SESSION['log_ok']))
{
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Panel logowania</title>
</head>
<body style="font-family: 'Segoe UI';">
    <h1 style="size: 64px; color: OliveDrab" >Panel logowania</h1>
        <form style="font-size: 16px;" method="post">
            <label>Użytkownik:</label>
            <br/>
            <input type="text" name="username">
            <br/>
            <label>Hasło:</label>
            <br/>
            <input type="password" name="password">
            <br/>
            <input type="submit" value="Zaloguj">
            <br/>
        </form>

<div style="size: 20px;">
<?PHP
    if(isset($_POST['password']))
    {

    require_once "config.php";

    $polaczenie = @new mysqli("$host","$dbuser","$dbpass","$db");
    if($polaczenie->connect_errno!=0)
    echo "Błąd: ".$polaczenie->errno;

    else
    {      
        $zaszyfr = hash('sha512',$_POST['password']);
        $login = "SELECT * FROM userinfo WHERE username='$_POST[username]' AND password='$zaszyfr'";

        if($rekord = @$polaczenie->query($login))
        {
            $ile = $rekord->num_rows;
            if($ile>0)
            {
                echo "Zalogowano pomyślnie.";

                $pole = $rekord->fetch_assoc();
                $_SESSION['imie'] = $pole['imie'];
                $_SESSION['nazwisko'] = $pole['nazwisko'];
                header('Location: index.php');
                unset($_SESSION['log_no']);
                unset($_SESSION['log_out']);
                $_SESSION['log_ok'] = '<span style="color: Turquoise">Już zalogowano</span>';
            }
            else
            {
                unset($_SESSION['log_out']);
                $_SESSION['log_no'] = '<span style="color: crimson">Niepoprawne hasło lub login, spróbuj jeszcze raz.</span>';
                echo $_SESSION['log_no'];
            }
        }
        $polaczenie->close();
    }
}
?>
<?PHP
    if(isset($_SESSION['log_out']))
    {
        echo $_SESSION['log_out'];
    }
?>
</div>

</br></br>

<div>
Nie masz konta? <a href="signup.php" style="color: DeepSkyBlue">Zarejestruj się tutaj!</a>
</div>

</body>
</html>