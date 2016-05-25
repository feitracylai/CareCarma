<?php
/**
 * Created by PhpStorm.
 * User: simonxu14
 * Date: 5/24/2016
 * Time: 5:20 PM
 */
//****************************************************
//****************************************************
//******  this code is to create a new message  ******
//****************************************************
//****************************************************

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
//title is the message title
//created by is the user_id of the user who send/create the new message
//send_to includes all the user_id of the account which the message creator want to send to. like "1,4,6"
//only if the user add a file in this message, add a file_id within the message
//the content is the content of the message
if( $_GET["title"] || $_GET["created_by"] || $_GET["send_to"] || $_GET["file_id"] || $_GET["content"] ) {
    echo "title: ". $_GET["title"]. "<br>";
    echo "created_by: ". $_GET["created_by"]. "<br>";
    echo "send_to: ". $_GET["send to"]. "<br>";
    echo "file_id: ". $_GET["file_id"]. "<br>";
    echo "content: ". $_GET["content"]. "<br>";
}

//************************************************
//*** add a new message into the table message ***
//************************************************
$title = $_GET["title"];
$created_at = date("Y-m-d H:i:sa");
$created_by = $_GET['created_by'];
$updated_at = date("Y-m-d H:i:sa");
$updated_by = $_GET['created_by'];

$sql = "INSERT INTO message (title, created_at, created_by, updated_at, updated_by) VALUES ('$title', '$created_at', '$created_by', '$created_at', '$created_by')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//the auto generated message_id
$message_id = mysqli_insert_id($conn);
echo $message_id;

//**************************************************************************************
//*** add new data into the table user_message for each user involved in the message ***
//**************************************************************************************
//for each account/user involved in this message, add an item into the table
//add item into table for the message originator
$user_id = $created_by;
$is_originator = 1;
$created_at = date("Y-m-d H:i:sa");
$updated_at = date("Y-m-d H:i:sa");
$last_viewed = date("Y-m-d H:i:sa");
$sql = "INSERT INTO user_message (message_id, user_id, is_originator, last_viewed, created_at, created_by, updated_at, updated_by) VALUES ('$message_id', '$user_id', '$is_originator', '$last_viewed', '$created_at', '$created_by', '$created_at', '$created_by')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
}
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//add item into table for the message receiver
$send_to = $_GET["send_to"];
$send_array = explode(',',$send_to);

for($index=0; $index<count($send_array); $index++){
    $user_id = $send_array[$index];
    $is_originator = null;
    $last_viewed = null;
    $created_at = date("Y-m-d H:i:sa");
    $updated_at = date("Y-m-d H:i:sa");
    $sql = "INSERT INTO user_message (message_id, user_id, created_at, created_by, updated_at, updated_by) VALUES ('$message_id', '$user_id', '$created_at', '$created_by', '$created_at', '$created_by')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    }
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

//****************************************************
//*** add the message into the table message_entry ***
//****************************************************
$user_id = $created_by;
$file_id = null;
$content = $_GET['content'];
$created_at = date("Y-m-d H:i:sa");
$created_at = date("Y-m-d H:i:sa");

$sql = "INSERT INTO message_entry (message_id, user_id, content, created_at, created_by, updated_at, updated_by) VALUES ('$message_id', '$user_id', '$content', '$created_at', '$created_by', '$created_at', '$updated_by')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//finish all the work and close the connection
$conn->close();