<?php
//<<<<<<< HEAD
$ftp_server = '192.168.201.234';
$ftp_user_name = 'nomina';
$ftp_user_pass = 'nomin4';
$local_file = 'temps/bajado.sql';
$remote_file = 'desno.dbf';
/*=======
$ftp_server = '192.168.201.250';
$ftp_user_name = 'nomina';
$ftp_user_pass = 'nomin4';
$local_file = 'temp/index.php';
$remote_file = 'a.txt';
>>>>>>> origin/master
*/

// establecer una conexi�n b�sica
$conn_id = ftp_connect($ftp_server); 

// iniciar una sesi�n con nombre de usuario y contrase�a
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

// verificar la conexi�n
if ((!$conn_id) || (!$login_result)) {  
    echo "�La conexi�n FTP ha fallado!";
    echo "Se intent� conectar al $ftp_server por el usuario $ftp_user_name"; 
    exit; 
} else {
    echo "Conexi�n a $ftp_server realizada con �xito, por el usuario $ftp_user_name";
}
echo "<br />";

// bajar un archivo
$download = ftp_get($conn_id, $local_file, $remote_file, FTP_BINARY);  

// comprobar el estado de la bajada
if (!$download) {  
    echo "�La bajada FTP ha fallado!";
} else {
    echo "Bajada de $remote_file a $ftp_server como $local_file";
}
echo "<br />";

// cerrar la conexi�n ftp 
ftp_close($conn_id);
?>
