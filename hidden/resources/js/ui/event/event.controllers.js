/**
 * Created by Florian on 05.04.2016.
 */
pwm.controller('PartyWithMeController', ['$scope', 'PartyWithMeService', 'ageConstants', function ($scope, pwmService, ageConstants) {
    $scope.getFilteredData = pwmService.filters.getData;
    $scope.getData = pwmService.getData;
    $scope.isDataAvailable = () => {
        let data = pwmService.getData();
        return !(data === undefined || data.length === 0);
    };
    $scope.initialized = false;
    $scope.interestedChanged = (isShowAll) => {
        pwmService.filters.updateInterested(isShowAll);
        pwmService.filters.updateFilteredData();
    };
    let minMaxChanged = () => {
        let ageFilter = pwmService.filters.age;
        pwmService.filters.updateAge($scope.minAge, $scope.maxAge);
        const newMin = ageFilter.min(), newMax = ageFilter.max();
        if (newMax !== $scope.maxAge || newMin !== $scope.minAge) {
            if ($scope.minAge !== null && $scope.minAge !== undefined && $scope.minAge !== ageConstants.min()) {
                $scope.minAge = newMin;
            }
            if ($scope.maxAge !== null && $scope.maxAge !== undefined && $scope.maxAge !== ageConstants.max()) {
                $scope.maxAge = newMax;
            }
        }
        pwmService.filters.updateFilteredData();
    };
    $scope.$watch('minAge', minMaxChanged);
    $scope.$watch('maxAge', minMaxChanged);

    pwmService.update((data) => {
        if (data !== undefined && data.length > 0) {
            let needsChange = true;
            for (let i = 0; i < data.length && needsChange; i++) {
                if (data[i].interested === '1') {
                    needsChange = false;
                }
            }
            if (needsChange) {
                let iSwitch = document.getElementById('interestedSwitch');
                let parent = iSwitch.parentElement;
                if (parent !== undefined && parent.MaterialSwitch !== undefined) {
                    $scope.isShowAll = true;
                    parent.MaterialSwitch.on();
                    $scope.interestedChanged(true);
                }
            }
            $scope.initialized = true;
        }
    });
    $scope.interestedChanged(false);
}]);
pwm.controller('EventController', ['$scope', 'pwmApi', 'pwmRequestApi', 'PartyWithMeService', 'MatchService', 'ga', 'log',
    function EventController($scope, pwmApi, pwmRequestApi, pwmService, matchService, ga, log) {
        let fEv = false, fSelf = false;
        $scope.event = {};
        $scope.self = {};
        $scope.found = false;
        $scope.fetched = () => fEv && fSelf;
        const eventEndpoint = 'getEvent.php';
        const selfEndpoint = 'getSelf.php';
        pwmApi(eventEndpoint, {"eventId": EVENT_ID}).then(function onComplete(res) {
            log.dlCompleted(res.data, eventEndpoint);
            $scope.event = res.data[0];
            fEv = true;
            $scope.found = res.data.length === 1;
            pwmRequestApi.set($scope.event.partywithme === '1');
            if (fEv) {
                document.title += ' - ' + res.data[0].name;
            }
        }, function onError(res) {
            log.dlError(res);
            fEv = true;
        });
        pwmApi(selfEndpoint, {}).then(function onComplete(res) {
            const data = res.data[0];
            log.dlCompleted(data, selfEndpoint);
            $scope.self = data;
            fSelf = true;
        }, function onError() {
            log.dlError(res);
            fSelf = true;
        });
        // Prepare Matching dialog
        let eventListenerSet = false;
        let matchGameStartButton = document.getElementById('matchingGameStart');
        matchGameStartButton.addEventListener('click', () => {
            let matchGameDialog = document.getElementById('matchDialog');
            //Check if dialog polyfill is needed
            if (!matchGameDialog.showModal) {
                dialogPolyfill.registerDialog(matchGameDialog);
            }
            if (!eventListenerSet) {
                let nodelist = matchGameDialog.getElementsByClassName('close');
                for (let i = 0; i < nodelist.length; i++) {
                    nodelist.item(i).addEventListener('click', () => matchGameDialog.close());
                }
                eventListenerSet = true;
            }
            matchGameDialog.showModal();
            matchService.init();
        });

    }]);
