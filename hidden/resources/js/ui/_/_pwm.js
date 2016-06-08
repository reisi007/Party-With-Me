"use strict";
/**
 * Created by Florian on 05.03.2016.
 */
let pwm = angular.module('partyWithMe', []);
pwm.factory('pwmApi', ['$http', function ($http) {
    return function (filename, data) {
        let url = PWM_API_URL + filename;
        return $http({
            method: 'POST',
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function (obj) {
                let str = [];
                for (let p in data)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            },
            data: data
        })
    };
}]);
pwm.directive('backImg', function () {
    return function (scope, element, attrs) {
        attrs.$observe('backImg', function (url) {
            element.css({
                'background-image': 'url(' + url + ')',
                'background-size': 'cover'
            });
        });
    };
});
pwm.factory('getTemplate', function () {
    return function (templateName) {
        return PWM_UI_TEMPLATE_URL + templateName + '.html';
    }
});

pwm.controller('HeaderController', ['$scope', 'ga', function ($scope, ga) {
    $scope.getMainPageUrl = function () {
        return PWM_UI_URL;
    };
    $scope.track = function (type) {
        ga.click('Header-' + type);
    };
    $scope.isLoggedIn = function () {
        return PWM_USER_ID !== undefined;
    }
}]);

pwm.factory('ga', function () {
    function _click(label, value = undefined) {
        _event('click', label);
    }

    function _event(type, label, value = undefined) {
        ga('send', 'event', type, label, value);
    }

    return {
        "click": _click,
        "event": _event
    }
});
//Check for adblocker
/*document.addEventListener('DOMContentLoaded', function () {
 let element = document.getElementById('ad-wrap');
 let displayStyle = undefined;
 if (element.currentStyle) {
 displayStyle = elem.currentStyle.display;
 } else if (window.getComputedStyle) {
 displayStyle = window.getComputedStyle(element, null).getPropertyValue("display");
 }
 if (displayStyle === 'none') {
 let h1 = document.createElement('h1');
 h1.className = 'ab';
 h1.innerHTML = 'Please disable your adblocker!';
 document.body.appendChild(h1)
 }
 }, false);*/
// Show cookie warning
window.showCookieWarning = function () {
    let done = false;
    document.addEventListener("mdl-componentupgraded", function () {
        if (!done) {
            let snackbarContainer = document.querySelector('#snackbar-cookies');
            if (snackbarContainer.MaterialSnackbar !== undefined) {
                let handler = function () {
                    window.location = PWM_UI_URL + 'legalinfo.php#privacy'
                };
                let data = {
                    message: 'This website uses cookies.',
                    timeout: 10000,
                    actionHandler: handler,
                    actionText: 'Privacy Policy'
                };
                done = true;
                snackbarContainer.MaterialSnackbar.showSnackbar(data);
            }
        }
    });
};