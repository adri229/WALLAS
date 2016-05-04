'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('TypeModalController', ['$scope', '$uibModalInstance', 'TypeService', 'items',
	function($scope, $uibModalInstance, TypeService, items) {

		console.log(items);
		
		$scope.insert = function(type) {
			console.log("insert type");
			console.log(type);
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

		$scope.update = function(type) {
			TypeService.update(type,items.idType).then(
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
			TypeService.delete(items.idType).then(
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