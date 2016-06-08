<div class="impressum" ng-controller="ImpressumController as iCtrl">
    <div>
        <h1>Impressum</h1>
        <ul>
            <li class="mdl-list__item">
    <span class="mdl-list__item-primary-content">
    <i class="material-icons mdl-list__item-icon">person</i>
    Florian Reisinger  </span>
            </li>
            <li class="mdl-list__item">
    <span class="mdl-list__item-primary-content">
    <i class="material-icons mdl-list__item-icon">mail_outline</i>
   <a ng-init="user = 'florian'" ng-href="mailto:{{getEmailFor(user)}}" target="_blank">{{getEmailFor(user)}}</a></span>
            </li>
        </ul>
    </div>
    <div>
        <h1>Credits</h1>
        <ul>
            <li ng-repeat="c in credits" class="mdl-list__item">
    <span class="mdl-list__item-primary-content">
    <i class="material-icons mdl-list__item-icon">chevron_right </i>
    <a ng-href="{{c.url}}">{{c.name}}</a> - <a ng-href="{{c.lurl}}">({{c.licence}})</a>  </span>
            </li>
    </div>
    <div>
        <a name="privacy"></a>
        <h1>Privacy Policy</h1>
    <span class="whiteSpace"><?php require 'privacyPolicy.txt';
        ?>
    </span>
    </div>
</div>