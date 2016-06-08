/**
 * Created by Florian on 05.04.2016.
 */
pwm.factory('sendFbMessage', ['ga', function (ga) {
    return function (id) {
        let url = 'https://www.facebook.com/app_scoped_user_id/' + id;
        ga.click('Open profile page', id);
        window.open(url);
    };
}]);

pwm.factory("PartyWithMeService", ['pwmData', 'pwmRequestApi', 'filterFilter', 'ageConstants', function (pwmData, pwmRequestApi, filterFilter, ageConstants) {
    //Variables
    let data = [];
    let filteredData = [];
    let instance = {};
    let interestedFilterData = undefined;
    let minMaxAge = {min: ageConstants.min(), max: ageConstants.max()};
    //Fill instance
    instance.update = (onFinished) => pwmData((newData)=> {
        data = newData;
        instance.filters.updateFilteredData();
        if (onFinished !== undefined)
            onFinished(data);
    });
    instance.getData = ()=>[].concat(data);
    instance.filters = {};
    instance.filters.getData = function () {
        return [].concat(filteredData);
    };
    //Filters
    instance.filters.age = {min: ()=> minMaxAge.min, max: ()=> minMaxAge.max};
    instance.filters.updateAge = function (min, max) {
        //Input verification
        if (min > max) {
            let t = min;
            min = max;
            max = t;
        }
        if (min === undefined || min === null)
            min = ageConstants.min();
        if (max === undefined || max === null)
            max = ageConstants.max();
        minMaxAge.min = min;
        minMaxAge.max = max;
    };
    instance.filters.age.filter = function (person) {
        let pAge = person.age;
        return minMaxAge.min <= pAge && pAge <= minMaxAge.max;
    };
    instance.filters.interested = (person) => {
        return interestedFilterData === undefined || person.interested === interestedFilterData;
    };
    instance.filters.updateInterested = function (isShowAll) {
        if (isShowAll) {
            interestedFilterData = undefined;
        }
        else {
            interestedFilterData = '1';
        }
    };
    instance.filters.applyAll = (person)=> {
        return instance.filters.interested(person) && instance.filters.age.filter(person);
    };
    instance.filters.updateFilteredData = () => {
        filteredData = filterFilter(data, instance.filters.applyAll);
    };
    instance.filters.updateAll = function (min, max, isShowAll) {
        let filters = instance.filters;
        filters.updateAge(min, max);
        filters.updateInterested(isShowAll);
        instance.filters.updateFilteredData();
    };
    return instance;
}]);