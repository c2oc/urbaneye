<?php
	session_start(
	    [
        'read_and_close'  => true,
    ]
    );
    if (isset($_SESSION["userSession"])){
    	echo json_encode(array('usersession' => $_SESSION["userSession"])); 
    } else {
            echo json_encode(array('usersession' => 0)); 
    }