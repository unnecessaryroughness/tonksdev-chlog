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
        <form name="frmLogout" action="/login/index.php" method="POST">
            <?php $unn = chlog\safeget::session("user", "nickname", "not logged in");
                    htmlout($unn); ?>

            <?php if ($unn!="not logged in"): ?>
                <button id="btnLogout" name="action" 
                        class="button-noborder" value="logout">(Log Out)</button>
            <?php endif; ?>
        </form>
    </div>
</header>

