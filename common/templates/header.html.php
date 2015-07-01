<header id="chlog-header">
    <div class="chlog-header">
        <a href="/">chLOG Application [<?php htmlout($_SESSION["environment"]->envid); ?>]</a>
    </div>
    <p>
        <form name="frmLogout" action="/login/index.php" method="POST">
            <?php $unn = chlog\safeget::session("user", "nickname", "not logged in");
                    htmlout($unn); ?>

            <?php if ($unn!="not logged in"): ?>
                <button id="btnLogout" name="action" 
                        class="button-noborder" value="logout">(Log Out)</button>
            <?php endif; ?>
        </form>
    </p>
    <hr>
</header>

