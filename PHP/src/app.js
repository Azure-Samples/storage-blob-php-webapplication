require('angular')
require('angular-route')

var app = angular.module('app', ['ngRoute'])
.run(function($rootScope,$location,$http){
	$rootScope.basePath = $location.protocol()+"://"+$location.host()+":"+location.port+"/";
	var history = [];

    $rootScope.$on('$routeChangeSuccess', function() {
        history.push($location.$$path);
    });

    $rootScope.back = function () {
        var prevUrl = history.length > 1 ? history.splice(-2)[0] : "/";
        $location.path(prevUrl);
    };
})
.config(function($routeProvider, $locationProvider) {
		// when functional models finished, change the route to dynamic
		$routeProvider
			.when('/',{
				templateUrl: 'views/main.html',
				controller:'MainController'
			})
			.when('/trial', {
				templateUrl: 'views/trial-container.html',
				controller: 'TrialController',
				resolve:function(){
					// $scope.currentParent='user';
				}
			})
			.when('/trial/:containerName', {
				templateUrl: 'views/trial-blobs.html',
				controller: 'BlobsController',
				resolve:function(){
					// $scope.currentParent='user';
				}
			})
			.when('/trial/:containerName/:params*', {
				templateUrl: 'views/trial-blobs.html',
				controller: 'BlobsController',
				resolve:function(){
					// $scope.currentParent='user';
				}
			})
			.otherwise({
				redirectTo: '/'
			})
			$locationProvider.html5Mode({enabled: true});	
	})
.controller('MainController', require('./controllers/MainController'))
.controller('TrialController', require('./controllers/TrialController'))
.controller('BlobsController', require('./controllers/BlobsController'))
.directive('blobVideo',require('./directives/VideoDirective'))
.directive('loading',require('./directives/loadDirective'))