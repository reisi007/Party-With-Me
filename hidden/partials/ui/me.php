<div class="me" ng-controller="MeController as meCtrl" ng-cloak>
    <div class="mdl-spinner mdl-js-spinner is-active" ng-show="me.id === undefined"></div>
    <div ng-show="me.id !== undefined">
        <ul class="demo-list-icon mdl-list">
            <li class="mdl-list__item"><span class="mdl-list__item-primary-content">    <i
                        class="material-icons mdl-list__item-icon">person</i>    {{me.firstName}} {{me.lastName}} ({{ ( me.gender === 'm' ? 'Male' : 'Female')}})</span>
            </li>
            <li class="mdl-list__item"><span class="mdl-list__item-primary-content">    <i
                        class="material-icons mdl-list__item-icon">account_box</i>   <img
                        ng-src="https://graph.facebook.com/{{me.id}}/picture?square">    </span>
            </li>
            <li class="mdl-list__item"><span class="mdl-list__item-primary-content">    <i
                        class="material-icons mdl-list__item-icon">cake</i>   {{me.birthday | date: 'shortDate'}}  </span>
            </li>
            <li class="mdl-list__item"><span class="mdl-list__item-primary-content">    <i
                        class="material-icons mdl-list__item-icon">mood</i>   Interested in: {{me.interested ==='b'?'Males and females':(me.interested ==='n'?'Not specified':(me.interested ==='f'?'Female':'Male')+'s')}}  </span>
            </li>
        </ul>
    </div> <?php use function at\eisisoft\partywithme\api\getLoginUrl;

    $actualUrl = $_SERVER['REQUEST_URI'];
    $loginUrl = getLoginUrl($actualUrl); ?>
    <div class="flexbox_h">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored"
                ng-click="updateData('<?= $loginUrl ?>')"> Update data
        </button>
        <button ng-click="deleteAccount()" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
            Delete account
        </button>
    </div>
</div>