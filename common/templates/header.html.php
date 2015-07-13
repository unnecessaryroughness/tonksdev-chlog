<header id="chlog-header">
    <div class="chlog-header">
        <a href="/">chLOG </a>
        <span class="chlog-header-environment">
            [<?php htmlout($_SESSION["environment"]->envid); ?>]
        </span>
        <div class="endfloat chlog-header-subheader">
            Cluster Headache Tracking 
        </div>
    </div>
    
    <div class="chlog-header-user">
            <?php 
                    $unn = chlog\safeget::session("user", "nickname", "not logged in");
                    htmlout($unn); 
                    if ($unn!="not logged in") {
                        echo " <a href='/login/?action=logout'>(Log Out)</a>";
                    }
            ?>
        </form>
    </div>
</header>

