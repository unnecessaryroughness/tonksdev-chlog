<?php 

    use chlog as ch;

    //autoload classes
    function __autoload($classname) {
        $parts = explode("\\", strtolower($classname));
        require $_SERVER['DOCUMENT_ROOT'] . end($parts) . '.php';
    }


    //--> TEST USERS

//    ch\User::registerUser("marktonks75@gmail.com", "Mark", "Tonksy", "testpw", "testpw");

    ch\User::setActive("f8a497ff4017d70dbf7cb05b8f5ce021a66fd27c566c416a92b9cd455a2db5ed");

    return true;

/*
    $usr = new ch\User("mark@tonks.me.uk", "Marko", 1);
    $usr = ch\User::getUserFromEmail("marktonks75@gmail.com", "xyz987");
    var_dump ($usr);
    unset($usr);


    $usr = ch\User::getUserFromEmail("marktonks75@gmail.com", "xyz987");
    var_dump ($usr);
    unset($usr);
*/
    

/*
    $usr->setEmail("aimztonks@gmail.com");
    $usr->setNickname("MarkT");
    $usr->setBiography("Long time episodic CH fighter!!!");
    $usr->setPassword("def123", "xyz987", "xyz987");

    echo $usr->Email()."\n";
    echo $usr->Nickname()."\n";
    echo $usr->Biography()."\n";
    echo $usr->JoinDate()."\n";
    echo $usr->IsAdmin()."\n";
    echo $usr->IsActive()."\n";

    echo ($usr->flushToDB("xyz987")) ? "Updated\n" : "No Update\n";
*/

    
    //ch\User::updateUser("marktonks75@gmail.com", "MarkT", "abc123", "abc123", "aac123");

    //$usr = ch\User::getUserFromEmail("marktonks75@gmail.com");
    //var_dump ($usr);

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

