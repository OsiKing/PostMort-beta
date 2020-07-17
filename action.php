<?php
/* Attempt MySQL server connection */
$link = mysqli_connect("us-cdbr-east-02.cleardb.com", "b9b673a811d170", "80bf61dc", "heroku_9b5821b3fd92857");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Escape user inputs for security
$email = mysqli_real_escape_string($link, $_REQUEST['email']);
 
// Attempt insert query execution
$sql = "INSERT INTO email_data (email) VALUES ('$email')";
if(mysqli_query($link, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);
?>
