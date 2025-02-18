<?php
$conn = mysqli_connect('localhost', 'root', '') ;
if(!$conn)
{
die('gagal konek'.mysqli_error($conn));
}
mysqli_select_db($conn, 'pertanian');
?>