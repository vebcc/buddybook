<?php

//include("config.php");

$ip = $_SERVER['REMOTE_ADDR'];

$akcja = $_GET['akcja'];
if ($akcja == 'wykonaj') {
    //
    $nick = substr(addslashes(htmlspecialchars($_POST['nick'])),0,32);
    $haslo = substr(addslashes($_POST['haslo']),0,32);
    $vhaslo = substr($_POST['vhaslo'],0,32);
    $email = substr($_POST['email'],0,32);
    $vemail = substr($_POST['vemail'],0,32);
    $nick = trim($nick);
    //kilka sprawdzen co do nicku i maila
    $spr1 = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM users WHERE login='$nick' LIMIT 1")); //czy user o takim nicku istnieje
    $spr2 = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM users WHERE email='$email' LIMIT 1")); // czy user o takim emailu istnieje
    $pos = strpos($email, "@");
    $pos2 = strpos($email, ".");
    $emailx = explode("@", $email);
    if ($emailx[1] == 'o2.pl') {
        $emailx1 = $emailx[0].'@go2.pl';
        $emailx2 = $emailx[0].'@tlen.pl';
        $spr3 = mysqli_fetch_array(mysqli_query($con,  "SELECT COUNT(*) FROM users WHERE email='$emailx1' OR `email`='$emailx2' LIMIT 1"));
    }elseif ($emailx[1] == 'go2.pl') {
        $emailx1 = $emailx[0].'@o2.pl';
        $emailx2 = $emailx[0].'@tlen.pl';
        $spr3 = mysqli_fetch_array(mysqli_query($con,  "SELECT COUNT(*) FROM users WHERE email='$emailx1' OR `email`='$emailx2' LIMIT 1"));
    }elseif ($emailx[1] == 'tlen.pl') {
        $emailx1 = $emailx[0].'@go2.pl';
        $emailx2 = $emailx[0].'@o2.pl';
        $spr3 = mysqli_fetch_array(mysqli_query($con,  "SELECT COUNT(*) FROM users WHERE email='$emailx1' OR `email`='$emailx2' LIMIT 1"));
    }
    $komunikaty = '';
    $spr4 = strlen($nick);
    $spr5 = strlen($haslo);
    //sprawdzenie co uzytkownik zle zrobil
    if (!$nick || !$email || !$haslo || !$vhaslo || !$vemail ) {
        $komunikaty .= "Musisz wypełnić wszystkie pola!<br>"; }
    if ($spr4 < 4) {
        $komunikaty .= "Login musi mieć przynajmniej 4 znaki<br>"; }
    if ($spr5 < 4) {
        $komunikaty .= "Hasło musi mieć przynajmniej 4 znaki<br>"; }
    if ($spr1[0] >= 1) {
        $komunikaty .= "Ten login jest zajęty!<br>"; }
    if ($spr2[0] >= 1) {
        $komunikaty .= "Ten e-mail jest już używany!<br>"; }
    if ($email != $vemail) {
        $komunikaty .= "E-maile się nie zgadzają ...<br>";}
    if ($haslo != $vhaslo) {
        $komunikaty .= "Hasła się nie zgadzają ...<br>";}
    if ($pos == false OR $pos2 == false) {
        $komunikaty .= "Nieprawidłowy adres e-mail<br>"; }
    if ($spr3[0] >= 1) {
        $komunikaty .= "Nie można zarejestrować kilku kont na jedną pocztę o2.<br>"; }

    //jesli cos jest nie tak to blokuje rejestracje i wyswietla bledy
    if ($komunikaty) {
        echo '
<b>Rejestracja nie powiodła się, popraw następujące błędy:</b><br>
'.$komunikaty.'<br>';
    } else {
        //jesli wszystko jest ok dodaje uzytkownika i wyswietla informacje
        $nick = str_replace ( ' ','', $nick );
        $haslo = md5($haslo); //szyfrowanie hasla

        mysqli_query($con,  "INSERT INTO `users` (login, email, password, ip) VALUES('$nick','$email','$haslo','$ip')") or die("Nie mogłem Cie zarejestrować!");

        echo '<br><span style="color: green; font-weight: bold;">Zostałeś zarejestrowany '.$nick.'. Teraz możesz się zalogować</span><br>';
        echo '<br><a href="logowanie.php">Logowanie</a>';
    }
}
?>

<form method="post" action="index.php?akcja=wykonaj">
    <table>
        <tr class="tlo-b"><td>Nick:</td>
            <td><input maxlength="18" type="text" name="nick" value="<?php echo $nick; ?>"></td></tr>
        <tr class="tlek"><td>Hasło:</td>
            <td><input maxlength="32" type="password" name="haslo"></td></tr>
        <tr class="tlo-b"><td>Powtórz hasło:</td>
            <td><input maxlength="32" type="password" name="vhaslo"></td></tr>
        <tr class="tlo-b"><td>E-mail:</td>
            <td><input type="text" name="email" maxlength="50" value="<?=$email?>"></td></tr>
        <tr class="tlek"><td>Powtórz E-mail:</td>
            <td><input type="text" maxlength="50" name="vemail" value="<?=$vemail?>"></span></td></tr>


<tr><td colspan="2" align="center"><input type="submit" value="Zarejestruj"></td></tr>
</table></form>

