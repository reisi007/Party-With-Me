/**
 * Created by Florian on 05.04.2016.
 */
pwm.controller('PersonCardController', ['$scope', 'sendFbMessage', 'fireEvent', function ($scope, sendFbMessage, fireEvent) {
    $scope.sendMessage = (id) => {
        sendFbMessage(id);
        fireEvent(EVENT_FB_MESSAGE_SEND, id)
    };

}]);

pwm.controller('PartyWithMeButtonController', ['$scope', 'pwmRequestApi', function ($scope, pwmRequestApi) {
    $scope.addPartyWithme = pwmRequestApi.add;
    $scope.removePartyWithme = pwmRequestApi.remove;
    $scope.isPartyWithme = () => pwmRequestApi.get() === 1;
}]);

pwm.controller('MatchDialogController', ['$scope', 'MatchService', 'log', function ($scope, matchService, log) {
    $scope.current = matchService.current;
    matchService.initWatcher.push(() => $scope.$apply());
    $scope.next = matchService.next;
    const eventName = EVENT_FB_MESSAGE_SEND;
    $scope.$on(eventName, (e, args) => {
        "use strict";
        log.log$on(eventName, args);
        $scope.next();
    })
}]);