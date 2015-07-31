app.directive('datepickerPopup', ['datepickerPopupConfig', 'dateParser', 'dateFilter', function (datepickerPopupConfig, dateParser, dateFilter) {
    return {
        'restrict': 'A',
        'require': '^ngModel',
        'link': function ($scope, element, attrs, ngModel) {
            var dateFormat;
            attrs.currentText ='今天';
            attrs.clearText ='清空';
            attrs.closeText ='关闭';
            //*** Temp fix for Angular 1.3 support [#2659](https://github.com/angular-ui/bootstrap/issues/2659)
            attrs.$observe('datepickerPopup', function(value) {
                dateFormat = value || datepickerPopupConfig.datepickerPopup;
                ngModel.$render();
            });

            ngModel.$formatters.push(function (value) {
                return ngModel.$isEmpty(value) ? value : dateFilter(value, dateFormat);
            });
        }
    };
}]);