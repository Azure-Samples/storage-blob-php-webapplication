module.exports = function() {
    return {
        restrict: 'E',
        transclude: true,
        scope: {
            show: '='
        },
        template: '<div class="la-ball-beat la-dark la-sm" ng-transclude ng-show="show"><div></div><div></div><div></div></div>',
        link: function(scope, element, attrs) {
            if (attrs.hasOwnProperty("ngShow")) {
                scope.$watch("ngShow", function(value) {
                    if (value) {
                        $element.show();
                    } else {
                        $element.hide();
                    }
                });
            }
        }
    }
}