<?php
namespace at\eisisoft\partywithme\ui;
require_once "hidden/partials/constants.php";

//Set HTML meta tag
$description = file_get_contents(resolveFileRelativeToHidden('resources/txt/meta-description.txt'));

require_once "hidden/partials/htmluiheader.php";

if (is_null($FB_LOGIN_VALID)) {
    require "hidden/partials/login.php";
} else {
    require "hidden/partials/ui/events.html";
}
?>
<?php require_once "hidden/partials/htmlfooter.php" ?>
