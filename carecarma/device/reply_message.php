<?php
/**
 * Created by PhpStorm.
 * User: simonxu14
 * Date: 5/24/2016
 * Time: 10:21 AM
 */
//***********************************************
//***********************************************
//******  this code is to reply a message  ******
//***********************************************
//***********************************************

$server = "aayw45sqck55w7.c81l6jttxgta.us-east-1.rds.amazonaws.com:3306";
$username = "root";
$password = "2439Lona";
$database = "ebdb";
date_default_timezone_set('America/Kentucky/Louisville');

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//print the form user input
//message_id is the message of the replay message
//user_id is the user who reply the message
//file_id only if user conclude a file
//content is the content of the reply message
if( $_GET["message_id"] || $_GET["user_id"] || $_GET["file_id"] || $_GET["content"]) {
    echo "message_id: ". $_GET["message_id"]. "<br>";
    echo "user_id: ". $_GET["user_id"]. "<br>";
    echo "file_id: ". $_GET["file_id"]. "<br>";
    echo "content: ". $_GET["content"]. "<br>";
}

//*********************************************************************
//*** retrieve the user_id of all the user involved in the message  ***
//*********************************************************************
// no need

//*********************************************************************
//*** add data of the reply message into the table message_entry  *****
//*********************************************************************
$message_id = $_GET["message_id"];
$user_id = $_GET["user_id"];
$file_id = null;
$content = $_GET['content'];
$created_at = date("Y-m-d H:i:sa");
$created_by = $user_id;
$updated_at = date("Y-m-d H:i:sa");
$updated_by = $user_id;

$sql = "INSERT INTO message_entry (message_id, user_id, content, created_at, created_by, updated_at, updated_by) VALUES ('$message_id', '$user_id', '$content', '$created_at', '$created_by', '$created_at', '$updated_by')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//*****************************************
//*** update the data in table message  ***
//*****************************************
$id = $message_id;
$updated_at = date("Y-m-d H:i:sa");
$updated_by = $user_id;

$sql = "UPDATE message SET updated_at='$updated_at', updated_by='$updated_by' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//****************************************
//*** update the data in user_message  ***
//****************************************
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