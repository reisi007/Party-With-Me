"use strict";
/**
 * Created by Florian on 23.03.2016.
 */
pwm.controller('ImpressumController', ['$scope', 'usedFLOSS', function ($scope, usedFLOSS) {
    const domain = 'partywithme.club';
    $scope.credits = usedFLOSS;
    $scope.getEmailFor = function (un) {
        return un + '@' + domain;
    }
}]);

pwm.constant('usedFLOSS', [
    {
        name: 'AngularJS',
        url: 'https://angularjs.org/',
        licence: 'The MIT License',
        lurl: 'https://github.com/angular/angular.js/blob/master/LICENSE'
    },
    {
        name: 'Material Design Lite',
        url: 'https://www.getmdl.io/',
        licence: 'Apache License Version 2.0',
        lurl: 'https://github.com/google/material-design-lite/blob/master/LICENSE'
    },
    {
        name: 'Material Design Icons',
        url: 'https://design.google.com/icons/',
        licence: 'CC-BY 4.0',
        lurl: 'https://creativecommons.org/licenses/by/4.0/'
    }
]);