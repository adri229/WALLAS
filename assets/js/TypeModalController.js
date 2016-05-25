'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('TypeModalController', ['$scope', '$uibModalInstance', 'TypeService', 'types',
	function($scope, $uibModalInstance, TypeService, types) {
		
		$scope.insert = function(type) {
			TypeService.create(type).then(
	    		function(response) {
	    			$uibModalInstance.close('closed');
	    		},
	    		function(response) {
	    			alert("error create");
	            	console.log(response);
	    		}

    		)
		}

		if (types != null) {
			$scope.type = types.type;	
		}
		

		$scope.update = function(type) {
			TypeService.update(type,types.type.idType).then(
				function(response) {
	    			$uibModalInstance.close('closed');
	    		},
	    		function(response) {
	    			alert("error update TypeModalController");
	            	console.log(response);
	    		}

			);
		}

		$scope.delete = function() {
			TypeService.delete(types.idType).then(
				function(response) {
					$uibModalInstance.close('closed');	
				},
				function(response) {
	    			alert("error delete TypeModalController");
	            	console.log(response);
	    		}
			);
		}



	    $scope.submitForm = function () {
	    	console.log("HELLO");    
	        $uibModalInstance.close('closed');
	       
	    };

	    $scope.cancel = function () {
	        $uibModalInstance.dismiss('cancel');
	    };
	}]);