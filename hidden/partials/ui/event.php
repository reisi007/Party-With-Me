<?php
use at\eisisoft\partywithme\Helper;

?>
<div class="event" ng-controller="EventController as eCtrl" ng-cloak>
    <div class="center" ng-show="fetched() && !found">
        <h3>Event does not exist or you did not add it on Facebook!</h3>
        <ul class="demo-list-icon mdl-list">
            <li class="mdl-list__item">
                <span class="mdl-list__item-primary-content">    <i class="material-icons mdl-list__item-icon">chevron_right</i>
                    <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" target="_blank"
                       href="https://www.facebook.com/events/<?= $jsConst['EVENT_ID'] ?>/">Add yourself to the
                        event</a>
                </span>
            </li>
            <li class="mdl-list__item">
                <div class="mdl-list__item-primary-content"><i
                        class="material-icons mdl-list__item-icon">chevron_right</i>
                    <form action="event.php" method="get">
                        <input type="hidden" name="id" value="<?= $jsConst['EVENT_ID']; ?>"/>
                        <input type="hidden" name="force" value="<?php
                        $force = Helper::getVariable('force', -1);
                        echo($force <= 0 ? time() : $force);
                        ?>"/>
                        <input type="submit" value="Reload page"
                               class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"/>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <div>
        <div class="mdl-spinner mdl-js-spinner is-active" ng-show="!fetched() && !found"
        ></div>
        <div class="mdl-card mdl-shadow--2dp" ng-show="found">
            <div class="mdl-card__title large" back-img="{{event.coverUrl}}">
                <h2 class="mdl-card__title-text readableText">{{event.name}}</h2>
            </div>
            <div class="mdl-card__supporting-text whiteSpace">{{event.description}}
            </div>
        </div>
        <br>
        <div ng-show="found" ng-cloak>
            <div class="mdl-card__title flexbox">
                <h2 class="mdl-card__title-text">Party with me!</h2>
                <button id="matchingGameStart"
                        class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                    Matching game
                </button>
                <pwm-pwm-button ng-show="fetched()"></pwm-pwm-button>
            </div>
            <div class="pwm" ng-controller="PartyWithMeController as pwmCtrl">
                <div class="mdl-spinner mdl-js-spinner is-active" ng-show="found && !fetched()"
                ></div>
                <h1 class="small" ng-show="fetched() && !isDataAvailable()">No Party with me requests yet!</h1>
                <div ng-show="fetched() && isDataAvailable()">
                    <div class="flexbox controls">
                        <label ng-hide="self.interested === 'n' || self.interested === 'b'"
                               class="mdl-switch mdl-js-switch mdl-js-ripple-effect interested"
                               for="interestedSwitch">
                            <input
                                type="checkbox" ng-model="isShowAll"
                                ng-change="interestedChanged(isShowAll)"
                                id="interestedSwitch" class="mdl-switch__input">
                            <span class="mdl-switch__label">Show all</span>
                        </label>
                        <div>
                            Between
                            <div class="mdl-textfield mdl-js-textfield age">
                                <input class="mdl-textfield__input" min="14" max="150" type="number"
                                       ng-model="minAge"
                                       id="min-age">
                                <label class="mdl-textfield__label" for="min-age">14</label>
                                <label class="mdl-textfield__error" for="min-age">Not a number or not in
                                    range</label>
                            </div>
                            and
                            <div class="mdl-textfield mdl-js-textfield age">
                                <input class="mdl-textfield__input" min="14" max="150" type="number"
                                       ng-model="maxAge"
                                       id="max-age">
                                <label class="mdl-textfield__label" for="max-age">150</label>
                                <label class="mdl-textfield__error" for="max-age">Not a number or not in
                                    range</label>
                            </div>
                            years old.
                        </div>
                    </div>
                    <h1 class="small" ng-show="fetched() && getFilteredData().length == 0">Please change your filter
                        criterias </h1>
                    <div class="flexbox">
                        <pwm-personCard class="mdl-shadow--2dp" person="person"
                                        ng-repeat="person in getFilteredData() track by person.id">
                        </pwm-personCard>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<match-dialog></match-dialog>