/**
 * Created by Florian on 04.04.2016.
 */
pwm.factory('log', function () {
    function formatEndpoint(endpoint) {
        if (endpoint === undefined) {
            return '';
        }
        else {
            return ' [' + endpoint + ']';
        }
    }

    let instance = {};
    instance.error = (error, type = undefined)=> console.err('[ERR] ', type, error);
    instance.info = (message, type = undefined) => console.log('[INFO]', type, message);
    instance.dlCompleted = (data, endpoint = undefined) => instance.info(data, '[DL completed]' + formatEndpoint(endpoint));
    instance.dlError = (res, endpoint = undefined) => instance.error(res, 'DL error]' + formatEndpoint(endpoint));
    instance.log$broadcast = (name, data = undefined) => instance.info(data, '[BROADCAST-SEND-' + name + ']');
    instance.log$on = (name, data = undefined) => instance.info(data, '[BROADCAST-RECEIVE-' + name + ']');
    return instance;
});
pwm.factory('fireEvent', ['$rootScope', 'log', function ($rootScope, log) {
    return function (eventName, args = undefined) {
        log.log$broadcast(eventName, args);
        $rootScope.$broadcast(eventName, args);
    }
}]);

//Constants
pwm.factory('ageConstants', function () {
    const minAge = 14, maxAge = 150;
    return {
        min: ()=> minAge, max: ()=>maxAge
    };
});