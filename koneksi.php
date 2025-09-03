<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "db_mystar";

$conn = mysqli_connect($host,$username,$password,$database);

if($conn){
    echo "Koneksi berhasil";
}else{
    echo "Koneksi gagal";
}
?>