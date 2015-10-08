<header id="chlog-header">
    <div class="chlog-header">
        <a href="/">chLOG 
            <?php if ($_SESSION["environment"]->envid != "PRD") {
                        htmlout("[".$_SESSION["environment"]->envid."]");
                    } ?>
        </a>
        <div class="usersplit"></div>
        <span class="chlog-header-loggedinuser">
            <?php 
                    $unn = chlog\safeget::session("user", "nickname", "not logged in");
                    echo($unn."&nbsp;"); 
                    if ($unn!="not logged in") {
                        echo " <a href='/login/?action=logout'>(Log Out)</a>";
                    }
            ?>
        </span>
    </div>
</header>

