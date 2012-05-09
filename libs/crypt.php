<?php
include_once("libs.php");
include_once("models.php");
include_once("literal.php");

$klartext = "<request><id>bichol13@4cf3a654293ac</id></request>";
$crypttext = "dXgsX1wRRjUlOi47XA0TAX5na2cxXFNeVDk5Lnp2e2N+HWhvfH55AxIODlprOyInQl9fQzVpdQkVeQ56VTk5aTA7WlxWVTY4OyogV0MRaB0baS45EkJUQSUyOjN6HBAZAWRmZ3ZtHAEFA35menF9Dh9/cRtpdWgmV0NBXz4kLHle";
$key =  constant("CRYPT_PW");

$encoded = encode($klartext);
$decoded = decode($crypttext);

echo '<p><b>Klartext:</b> '.$klartext.'</p>';
echo '<p><b>Schl&uuml;ssel:</b> '.$key.'</p>';
echo '<p><b>verschl&uuml;sselt:</b> '.$encoded.'</p>';
echo '<p><b>Klartext, mit decode():</b> '.$decoded.'</p>';

?>