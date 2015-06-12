<?php 

    use chlog as ch;

    //autoload classes
    function __autoload($classname) {
        $parts = explode("\\", strtolower($classname));
        require $_SERVER['DOCUMENT_ROOT'] . end($parts) . '.php';
    }


    //--> TEST USERS
    //$usr = new ch\User("mark@tonks.me.uk", "Marko", 1);
    $usr = ch\User::getUserFromEmail("marktonks75@gmail.com");
    var_dump ($usr);

    ch\User::updateUser("marktonks75@gmail.com", "MarkT", "abc123", "abc123", "aac123");

    $usr = ch\User::getUserFromEmail("marktonks75@gmail.com");
    var_dump ($usr);

/*
    //--> TEST DATABASE 
    
    $dbc = new ch\database();
    
    try {
            $cn = $dbc->connect();
    } 
    catch (\Exception $e) {
        echo "failed... " . $e->getMessage() . "\n";
        exit();
    }
    
    echo "successfully connected to database.\n";
*/



    return true;

