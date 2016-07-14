module.exports = function($scope, $routeParams, $http) {
    $scope.container = $routeParams.containerName;
	var queryParams = {
		trial: 'blob',
        containerName: $scope.container
	}
	var preSetParams = ['blobPrefixes','blobName']
	if($routeParams.params){
		angular.forEach($routeParams.params.split("&"),function(item,index){
			if(item){
				queryParams[preSetParams[index]]=item;
			}
		});
	}
	$scope.getData = function(){
		console.log(queryParams);
		$http.get($scope.basePath + "api/trial.php", {
        	params: queryParams
	    })
	    .then(function(data) {
	        $scope.blobPrefixes = data.data.blobPrefixes;
	        $scope.blobs = $scope.blobs?$scope.blobs.concat(data.data.blobs):data.data.blobs;
	        $scope.delimiter = data.data.delimiter;
	        $scope.nextMarker = data.data.nextMarker;
	        $scope.prefixes = data.data.prefixes;
	    }, function(reason) {
	        console.log(reason);
	    })
	}
    $scope.getMore = function(){
    	queryParams.next = $scope.nextMarker;
    	$scope.getData();
    }
    $scope.getData();

    $scope.show={};
    $scope.getContent=function(index){
    	$http.get($scope.basePath + "api/trial.php",{
    		params:{
    			trial:'content',
    			blob:$scope.blobs[index].name
    		}
    	})
    	.then(function(data) {
    		$scope.blobs[index].content = data.data;
	    }, function(reason) {
	        console.log(reason);
	    })
    }
}