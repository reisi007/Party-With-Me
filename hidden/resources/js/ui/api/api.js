/**
 * Created by Florian on 05.04.2016.
 */
pwm.factory('pwmData', ['pwmApi', 'log', function (pwmApi, log) {
    const endpoint = 'pwm/';
    return function (onFetched) {
        let data = [];
        pwmApi(endpoint, {"eventId": EVENT_ID}).then(function onComplete(res) {
            log.dlCompleted(res.data, endpoint);
            data = res.data;
            onFetched(data);
        }, function onFailure(res) {
            log.dlError(res, endpoint);
            data = [];
            onFetched(data);
        });
    }
}]);
pwm.factory('pwmRequestApi', ['pwmApi', 'ga', 'log', function (pwmApi, ga, log) {
    let pwm = 0;
    let instance = {
        get: () => pwm,
        set: (next) => pwm = (!!next ? 1 : 0)
    };
    const endpointAdd = 'pwm/add.php', endpointDelete = 'pwm/delete.php';
    instance.add = function () {
        pwmApi(endpointAdd, {"eventId": EVENT_ID}).then(function onComplete(res) {
            instance.set(res.data.result);
            ga.event('PartyWithMe Request', 'Add');
            log.dlCompleted(res.data.result, endpointAdd);
        }, function onError(res) {
            log.dlError(res, endpointAdd);
            instance.set(1);
        })
    };
    instance.remove = function () {
        pwmApi(endpointDelete, {"eventId": EVENT_ID}).then(function onComplete(res) {
            instance.set(res.data.result);
            ga.event('PartyWithMeRequest', 'Delete');
            log.dlCompleted(res.data.result, endpointDelete);
        }, function onError(res) {
            log.dlError(res, endpointDelete);
            instance.set(0);
        })
    };
    return instance
}]);