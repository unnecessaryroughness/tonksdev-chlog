<header id="chlog-header">
    <h1><a href="/chlog/">chLOG Application [<?php htmlout($_SESSION["environment"]->envid); ?>]</a></h1>
    <p>
        <form name="frmLogout" action="/chlog/login/index.php" method="POST">
            <?php $unn = chlog\safeget::session("user", "nickname", "not logged in");
                    htmlout($unn); ?>

            <?php if ($unn!="not logged in"): ?>
                <button id="btnLogout" name="action" 
                        class="button-noborder" value="logout">Log Out</button>
            <?php endif; ?>
        </form>
    </p>
    <hr>
</header>

