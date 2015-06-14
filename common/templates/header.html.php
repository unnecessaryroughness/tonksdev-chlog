<header id="chlog-header">
    <h1>chLOG Application [<?php htmlout($_SESSION["environment"]->envid); ?>]</h1>
    <p><?php 
            echo chlog\safeget::session("user", "nickname", "not logged in"); ?></p>
    <hr>
</header>

