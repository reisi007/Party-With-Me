</div>
</main>
<footer class="mdl-mini-footer">
    <div>&copy; Reisisoft 2016 - <?= date("Y") ?></div>
    <div>
        <a href="legalinfo.php">Privacy & Terms</a>
    </div>
</footer>
<div id="snackbar-cookies" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>
</body>
<?php
if ($updateCookie) {
    echo '<script>window.showCookieWarning();</script>';
}
?>