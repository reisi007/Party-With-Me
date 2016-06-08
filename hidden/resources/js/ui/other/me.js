"use strict";
/**
 * Created by Florian on 20.03.2016.
 */
pwm.controller('MeController', ['$scope', 'pwmApi', 'ga', 'log', function ($scope, pwmApi, ga, log) {
    $scope.me = {};
    const endpoint = 'getSelf.php';
    pwmApi(endpoint, {}).then(function onComplete(res) {
        $scope.me = res.data[0];
        log.dlCompleted($scope.me, endpoint);
    }, function onError(res) {
        log.dlError(res, endpoint);
    });
    $scope.updateData = function (url) {
        ga.click('Update profile data', $scope.me.id);
        window.open(url, '_self');
    };
    $scope.deleteAccount = function () {
        let cont = confirm('Do you really want to delete your account? This cannot be undone!');
        if (cont) {
            ga.click('Deleted profile', $scope.me.id);
            window.open('/deleteAccount.php', '_self');
        }
    }
}]);