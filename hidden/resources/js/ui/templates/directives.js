/**
 * Created by Florian on 05.04.2016.
 */
pwm.directive('pwmPersoncard', ['getTemplate', function (getTemplate) {
    return {
        restrict: 'E',
        templateUrl: getTemplate('personCard'),
        controller: 'PersonCardController',
        scope: {
            person: '='
        }
    }
}]);
pwm.directive('pwmPwmButton', ['getTemplate', function (getTemplate) {
    return {
        restrict: 'E',
        templateUrl: getTemplate('pwmButton')
    }
}]);
pwm.directive('matchDialog', ['getTemplate', function (getTemplate) {
    return {
        restrict: 'E',
        templateUrl: getTemplate('matchDialog'),
        controller: 'MatchDialogController'
    }
}]);