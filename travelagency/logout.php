<?php
session_start();
session_unset();
session_destroy();
header("Location: assets/pages/signin.html");
exit();
?>