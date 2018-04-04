<?php
if(isset($_GET['akcja'])){
$akcja = $_GET['akcja'];
}else{
    $akcja= '';
}
if ($akcja == 'login') {

$ollogin = $_POST['ollogin'];
$olhaslo = $_POST['olhaslo'];
$olhaslo = addslashes($olhaslo);
$ollogin = addslashes($ollogin);
$ollogin = htmlspecialchars($ollogin);

if(isset($_GET['ollogin'])){
    if ($_GET['ollogin'] != '') { //jezeli ktos przez adres probuje kombinowac
        exit;
    }
    if ($_GET['olhaslo'] != '') { //jezeli ktos przez adres probuje kombinowac
        exit;
    }
}

$olhaslo = md5($olhaslo); //szyfrowanie hasla
if (!$ollogin OR empty($ollogin)) {
    //include("head2.php");
    echo '<p class="alert">Wype³nij pole z loginem!</p>';
    //include("foot.php");
    exit;
}
if (!$olhaslo OR empty($olhaslo)) {
    //include("head2.php");
    echo '<p class="alert">Wype³nij pole z has³em!</p>';
    //include("foot.php");
    exit;
}
$istnick = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `users` WHERE `login` = '$ollogin' AND `password` = '$olhaslo'")); // sprawdzenie czy istnieje uzytkownik o takim nicku i hasle
if ($istnick[0] == 0) {
    echo 'Logowanie nieudane. Sprawdz pisownie nicku oraz hasla.';
} else {

    //$_SESSION['olnick'] = $ollogin;
    //$_SESSION['olhaslo'] = $olhaslo;

    echo 'Zostałeś zalogowany!';
    //header("Location: ../controlpanel.php");
}
}
?>

   <form method="POST" action="index.php?akcja=login">
    <table cellpadding="0" cellspacing="0" width="180">

        <tr><td><br></td></tr>
        <tr><td width="50">Login:</td><td><input type="text" name="ollogin" maxlength="32"></td></tr>
        <tr><td width="50">Hasło:</td><td><input type="password" name="olhaslo" maxlength="32"></td></tr>
        <tr><td align="center" colspan="2"><input type="submit" value="Zaloguj"><br></td></tr>

    </table>
</form>
