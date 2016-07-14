module.exports = function($rootScope,$compile) {
    return {
    	restrict:'E',
    	scope:{
    		blob:"="
    	},
    	link:function(scope,element,attrs){
    		var videoUrl = $rootScope.basePath+"api/trial.php?trial=content&blob="+scope.blob.name;
    		var videoDom = 	'<div class="embed-responsive embed-responsive-16by9">'+
    						'<video width="100%" controls>'+
    						'<source src="'+videoUrl+'" type="'+scope.blob.properties.contentType+'">'+
    					 	'</video>'+
    					 	'</div>';
    		angular.element(element).prepend($compile(videoDom)(scope));
    	}
    }
}
