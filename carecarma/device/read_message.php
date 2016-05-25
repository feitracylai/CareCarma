<?php
/**
 * Created by PhpStorm.
 * User: simonxu14
 * Date: 5/25/2016
 * Time: 11:51 AM
 */
//***********************************************************************
//***********************************************************************
//******  this code is to tell server user read the a new message  ******
//***********************************************************************
//***********************************************************************

//the server information
$server = "aayw45sqck55w7.c81l6jttxgta.us-east-1.rds.amazonaws.com:3306";
$username = "root";
$password = "2439Lona";
$database = "ebdb";
date_default_timezone_set('America/Kentucky/Louisville');

//using mysqli to connect mysql database
$conn = new mysqli($server, $username, $password, $database);

//check if connecting is good, print "Connected successfully if connect to the mysql database"
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//print the form user input
//message_id is the message user read
//user_id is the id of the user who read this message
if( $_GET["message_id"] || $_GET["user_id"]) {
    echo "message_id: ". $_GET["message_id"]. "<br>";
    echo "user_id: ". $_GET["user_id"]. "<br>";
}

//****************************************************
//*** update the information in table user_message ***
//****************************************************
$message_id = $_GET["message_id"];
$user_id = $_GET["user_id"];
$is_originator = null;
$last_viewed = date("Y-m-d H:i:sa");
$updated_at = date("Y-m-d H:i:sa");
$updated_by = $user_id;

$sql = "UPDATE user_message SET last_viewed='$last_viewed', updated_at='$updated_at', updated_by='$updated_by' WHERE message_id='$message_id' and user_id='$user_id'";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//finish all the work and close the connection
$conn->close();