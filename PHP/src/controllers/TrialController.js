module.exports = function($scope, $http) {
    $http.get($scope.basePath + "api/trial.php?trial=container")
        .then(function(data) {
            // console.log(data.data==='true')
            $scope.containers = data.data;
        }, function(reason) {
            console.log(reason); 
        })
}