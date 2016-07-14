module.exports = function($scope, $location, $http,$rootScope) {
    $scope.message = '';
    
    $scope.checkContainerInitial = function(){
        $http.get($scope.basePath + "api/trial.php?init=check")
        .then(function(data) {
            if(data.data==='true'){
                $rootScope.isInit = true;
            }
        }, function(reason) {
            console.log(reason); 
        })
    }

    $scope.createContainer = function(){
    	$scope.isShow_c = true;
    	$http.get($scope.basePath+"api/trial.php?init=container")
    		.then(function(data){
    			$scope.isShow_c = false;
    			$scope.message_c = data.data;
    		},function(reason){
    			console.log(reason);
    			$scope.isShow_c = false;
    			$scope.message_c = reason;
    		})
    }

    $scope.createTextBlob = function(){
    	$scope.isShow_t = true;
    	$http.get($scope.basePath+"api/trial.php?init=text")
    		.then(function(data){
    			$scope.isShow_t = false;
    			$scope.message_t = data.data;
    			$rootScope.isInit = true;
    		},function(reason){
    			console.log(reason);
    			$scope.isShow_t = false;
    			$scope.message_t = reason;
    		})
    }

    $scope.createVideoBlob = function(){
    	$scope.isShow_v = true;
    	$http.get($scope.basePath+"api/trial.php?init=video")
    		.then(function(data){
    			$scope.isShow_v = false;
    			$scope.message_v = data.data;
    			$rootScope.isInit = true;
    		},function(reason){
    			console.log(reason);
    			$scope.isShow_v = false;
    			$scope.message_v = reason;
    		})
    }

    $scope.checkContainerInitial();
}