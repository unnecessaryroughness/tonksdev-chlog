<div id="chlog-mainmenu-toggle" onclick="toggleMenu()"><img src="/common/threelines-grey.jpg">Main Menu</div>
<nav id="chlog-mainmenu">
    <ul>
        <li><a href="/">Home</a>
        </li><li class="dummya" submenu="chlog-attack-menu">Attack
        </li><li class="dummya" submenu="chlog-treatment-menu">Treatment
        </li><li class="dummya" submenu="chlog-reporting-menu">Reporting
        </li><li class="dummya" submenu="chlog-settings-menu">Settings
        </li><li><a href="/about/">About</a></li>
    </ul>
</nav>
<nav class="chlog-submenu" id="chlog-attack-menu">
    <section>
        <div class="chlog-submenu-header">Attack Options</div>
        <ul>
            <li><a href="/">I'm having an attack NOW</a></li>
            <li><a href="/">My attack is now over</a></li>
        </ul>    
    </section>
</nav>
<nav class="chlog-submenu" id="chlog-treatment-menu">
    <section>
        <div class="chlog-submenu-header">Treatment Options</div>
        <ul>
            <li><a href="/">My Treatment Plan</a></li>
        </ul>
    </section>
</nav>
<nav class="chlog-submenu" id="chlog-reporting-menu">
    <section>
        <div class="chlog-submenu-header">Reports</div>
        <ul>
            <li><a href="/">Report One</a></li>
            <li><a href="/">Report Two</a></li>
            <li><a href="/">Report Three</a></li>
        </ul>
    </section>
    <section>
        <div class="chlog-submenu-header">Charts</div>
        <ul>
            <li><a href="/">Chart One</a></li>
            <li><a href="/">Chart Two</a></li>
            <li><a href="/">Chart Three</a></li>
        </ul>
    </section>
</nav>
<nav class="chlog-submenu" id="chlog-settings-menu">
    <section>
        <div class="chlog-submenu-header">Settings</div>
        <ul>
            <?php 
                if (!chlog\safeget::session("user", "nickname", null)) {
                    echo "<li><a href='/login/'>Log In</a></li>";
                } else {
                    echo "<li><a href='/login/?action=logout'>Log Out</a></li>";
                } 
            ?>
            <li><a href="/useradmin/">User Preferences</a></li>
        </ul>
    </section>
    <section>
        <div class="chlog-submenu-header">Set Up Lists</div>
        <div class="chlog-submenu-linkcolumn">
            <ul>
                <li><a href="/triggers/">Triggers</a></li>
                <li><a href="/symptoms/">Symptoms</a></li>
                <li><a href="/locations/">Pain Locations</a></li>
            </ul>
        </div>
        <div class="chlog-submenu-linkcolumn">
            <ul>
                <li><a href="/treatments/">Treatments</a></li>
                <li><a href="/sideeffects/">Side Effects</a></li>
                <li><a href="/">Attack Waves</a></li>
            </ul>
        </div>
    </section>
</nav>

<div class="endfloat" id="chlog-menu-delimiter"></div>

<script src="/common/templates/mainmenu.js"></script>
