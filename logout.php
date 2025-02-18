<?php
session_start();
session_unset();
session_destroy();
header("Location: index.html"); // Ganti dengan halaman beranda atau halaman mana pun setelah logout
exit();
?>