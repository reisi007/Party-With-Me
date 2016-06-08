"use strict";
/**
 * Created by Florian on 07.03.2016.
 */
pwm.controller('EventListController', ['$scope', 'pwmApi', 'ga', 'log',
    function EventListController($scope, pwmApi, ga, log) {
        $scope.fetched = false;
        $scope.events = [];
        const endpoint = 'listEvents.php';
        pwmApi(endpoint, {}).then(function onComplete(res) {
            $scope.events = res.data;
            log.dlCompleted(res.data);
            $scope.fetched = true;
        }, function onError(res) {
            log.dlError(res, endpoint);
            $scope.fetched = true;
        });

        $scope.get3DigitName = function (number) {
            if (number > 99) {
                return '99+';
            }
            return Math.floor(number)
        };
        $scope.goToEvent = function (id) {
            let url = PWM_UI_URL + 'event.php?id=' + id;
            ga.click('event', id);
            window.open(url, '_self');
        }
    }]);