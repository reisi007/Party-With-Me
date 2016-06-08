<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header" ng-cloak>
    <header class="mdl-layout__header" ng-controller="HeaderController as hCtrl">
        <div class="mdl-layout__header-row">
            <a class="mdl-layout-title" ng-href="{{getMainPageUrl()}}" ng-click="track('Mainpaeg URL')">
                <img alt="Partywithme Logo" class="logo" src="<?= PARTYWITHME_UI_URL . 'png/logo.png' ?>"/>
                Party With Me!</a>
            <div class="mdl-layout-spacer"></div>
            <div ng-cloak ng-show="isLoggedIn()">
                <button ng-click="track('Menu right')" id="menu-right"
                        class="mdl-button mdl-js-button mdl-button--icon">
                    <i class="material-icons">more_vert</i>
                </button>

                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                    for="menu-right">
                    <li class="mdl-menu__item"><a ng-click="track('Profile page')" href="me.php"><i
                                class="material-icons">account_circle</i>Show
                            profile</a></li>
                    <li class="mdl-menu__item"><a ng-click="track('Logout')" href="logout.php"><i
                                class="material-icons">power_settings_new</i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
