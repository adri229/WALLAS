'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('TypeModalController', ['$scope', '$uibModalInstance', 'TypeService', 'types',
	function($scope, $uibModalInstance, TypeService, types) {
		
		$scope.create = function(type) {
			TypeService.create(type).then(
	    		function(response) {
	    			$uibModalInstance.close('closed');
	    		},
	    		function(response) {
	    			if (response.data == 'This type already exits') {
	    				$scope.msg = 'This type already exits';
	    			} else {
	    				$scope.msg = 'An error ocurred';
	    			}
	    			$scope.alertType = true;
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
	    			if (response.data == 'This type already exits') {
	    				$scope.msg = 'This type already exits';
	    			} else {
	    				$scope.msg = 'An error ocurred';
	    			}
	    			$scope.alertType = true;
	    		}

			);
		}

		$scope.delete = function() {
			TypeService.delete(types.idType).then(
				function(response) {
					$uibModalInstance.close('closed');	
				},
				function(response) {
	    			$scope.msg = 'An error ocurred';
	    			$scope.alertType = true;
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

	    $scope.closeAlert = function(index) {
        	$scope.alertType = false;
    	};
	}]);