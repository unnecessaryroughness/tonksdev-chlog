<?php 

    use chlog as ch;

    //autoload classes
    include_once 'common/chlogautoload.php';


    $sl = ch\Lookups::getSymptomsList("marktonks75@gmail.com");

    foreach ($sl as $s) {
        echo $s->symptomid."\t".$s->description."\t".$s->sortorder."\t".$s->hidden."\n";   
    }

    echo "-------\n";

    $sl->getSymptom(1)->update(0, 1);
    $sl->getSymptom(2)->update(25);

    ch\Lookups::updateSymptomsList("marktonks75@gmail.com", $sl);
    
    $sl->sort();
    foreach ($sl as $s) {
        echo $s->symptomid."\t".$s->description."\t".$s->sortorder."\t".$s->hidden."\n";   
    }

    echo "\nupdated.\n";


    //unset($sl[1]);

/*
    foreach ($sl as $s) {
        echo $s->symptomid."\t".$s->description."\t".$s->sortorder."\t".$s->hidden."\n";   
    }

    echo "\t\t\t\t".$sl->count()." records\n";
    
    echo "changed? ".$sl[1]->descriptionhaschanged."\n";

    
    $sl[1]->updateAdmin("hello kitty!", 5002, 1);
    
    echo "changed? ".$sl[1]->descriptionhaschanged."\n";

    $sl->sort();

    foreach ($sl as $s) {
        echo $s->symptomid."\t".$s->description."\t".$s->sortorder."\t".$s->hidden."\n";   
    }
*/
    


/*
    $sl = new ch\SymptomList();
    $sl->addSymptom("mark", 1, "test symptom", 1, 0);
    $sl->addSymptom("mark", 2, "test symptom two", 2, 0);

    //echo var_dump($sl);
    echo var_dump($sl->getSymptom(1));
*/


    //--> TEST USERS

//    ch\User::registerUser("marktonks75@gmail.com", "Mark", "Tonksy", "testpw", "testpw");

    //ch\User::setActive("f8a497ff4017d70dbf7cb05b8f5ce021a66fd27c566c416a92b9cd455a2db5ed");

    //return true;

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

