/**
 * Created by Florian on 06.04.2016.
 */
pwm.factory('MatchService', ['PartyWithMeService', function (pwmService) {
    let instance = {initWatcher: []};
    let cur = [];

    function _current() {
        return cur;
    }

    function _init() {
        instance.data = pwmService.filters.getData();
        cur.splice(0, cur.length);
        _next();
        instance.initWatcher.forEach(f => f());
    }

    function _next() {
        let next = null;
        let data = instance.data;
        let keys = Object.keys(data);
        if (keys.length === 0) {
            next = undefined;
        } else if (keys.length === 1) {
            next = data[keys[0]];
            delete data[keys[0]]
        } else {
            let index = Math.round(Math.random() * keys.length);
            next = data[keys[index]];
            delete data[keys[index]];
        }
        if (cur.length > 0) {
            cur.splice(0, cur.length)
        }
        if (next !== undefined)
            cur.push(next);
    }

    instance.current = _current;
    instance.init = _init;
    instance.next = _next;
    return instance;
}]);