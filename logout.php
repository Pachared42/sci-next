<?php
session_start();
session_unset();
session_destroy();

header("Location: /sci-shop-admin/index.php");
exit();