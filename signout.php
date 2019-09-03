<?php


include "header.php";

if (!isset($_SESSION['loggedIn']))
{
    echo "You must be logged in to view this page.<br>";
}
else
{
    // destroy the session data, clear the session superglobal array:
    $_SESSION = array();
    // Destroy cookie
    setcookie(session_name(), "", time() - 2592000, '/');
    // destroy session data on the server:
    session_destroy();

    echo "<div class='message'>You have successfully logged out, please <a href='login.php'>click here</a> to return to the login page.<div>";
}

// finish of the HTML for this page:
require_once "footer.php";

?>