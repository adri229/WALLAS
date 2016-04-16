'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('AuthenticationController',
['$scope', '$rootScope', '$location', 'AuthenticationService',
 function ($scope, $rootScope, $location, AuthenticationService) {
   
        AuthenticationService.clearCredentials();

        $scope.login = function() {
        	AuthenticationService.setCredentials($scope.credentials);
        	
			AuthenticationService.login($scope.credentials).then(
				function(response) {
					//AuthenticationService.setCredentials($scope.credentials);
					$location.path('/user');
				}, 
				function(response) {
					AuthenticationService.clearCredentials();
              		$scope.error = response.message;
              		alert("error");
				}
			);
        };
/*
        $scope.login = function() {
          AuthenticationService.setCredentials($scope.credentials.login,$scope.credentials.password);
          AuthenticationService.login($scope.credentials.login, $scope.credentials.password, function(response){
            if (response.success) {
              //AuthenticationService.setCredentials($scope.credentials.login,$scope.credentials.password);
              location.path('/');
            } else {
              AuthenticationService.clearCredentials();
              $scope.error = response.message;
            }
          });

          */
    
}]);



wallas.controller('RegisterController', ['$scope','UserService', function ($scope, UserService) {
        $scope.register = function() {
        	UserService.create($scope.user).then(
        		function(response) {
        			alert("success");
        		},
        		function(response) {
        			alert("error");
        			console.log(response);
        		}

        	);
        };
}]);


wallas.controller('UserController', ['$scope', '$cookies', 'UserService', function($scope, $cookies,UserService) {
	var user = $cookies.getObject('globals');

	var login = user.currentUser.login;
	


	UserService.getByLogin(login).then(
		function(response) {
			$scope.user = response;
		},
		function(response) {
			alert("error");
        	console.log(response);
		}
	);

	$scope.delete = function() {
		
		UserService.delete(login).then(
		function(response) {
			alert("DELETE USER");
		},
		function(response) {
			alert("error delete");
        	console.log(response);
		}

	);
	}

  $scope.update = function(attribute) {

    alert(attribute);

    var data = $scope.attributes;

    console.log(data);

    UserService.update(login,attribute,data).then(
        function(response) {
            alert("UPDATE " + attribute);
        },
        function(response) {
            alert("error delete");
            console.log(response);
        }        
    );
  }
	

	
}]); 




wallas.controller('RevenueController', ['$scope', '$cookies', 'RevenueService', 
  function($scope, $cookies, RevenueService) {

    var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.create = function() {
    	RevenueService.create($scope.revenue).then(
    		function(response) {
    			alert("Create revenue");
    		},
    		function(response) {
    			alert("error delete");
            	console.log(response);
    		}

    		);
    };


  	RevenueService.getByOwner(login).then(
  		function(response) {
			$scope.revenues = response;
			console.log($scope.revenues.data);
		},
		function(response) {
			alert("error");
        	console.log(response);
		}


	);

  	$scope.delete = function(idRevenue) {
  		RevenueService.delete(idRevenue).then(
		function(response) {
			alert("DELETE revenue");
		},
		function(response) {
			alert("error delete");
        	console.log(response);
		}
		)
  	};


  }]);


wallas.controller('TypeController', ['$scope', '$cookies', 'TypeService',
	function($scope, $cookies, TypeService) {

	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.create = function() {
    	TypeService.create($scope.type).then(
    		function(response) {
    			alert("Create type");
    		},
    		function(response) {
    			alert("error create");
            	console.log(response);
    		}

    	)
    };

	TypeService.getByOwner(login).then(
  		function(response) {
			$scope.types = response;
			console.log($scope.types.data);
		},
		function(response) {
			alert("error");
        	console.log(response);
		}


	);

  	$scope.delete = function(idType) {
  		TypeService.delete(idType).then(
		function(response) {
			alert("DELETE type");
		},
		function(response) {
			alert("error delete");
        	console.log(response);
		}
		)
  	};

}]);

wallas.controller('StockController', ['$scope', '$cookies', 'StockService',
	function($scope, $cookies, StockService) {

	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;

    $scope.create = function() {
    	StockService.create($scope.stock).then(
    		function(response) {
    			alert("Create stock");
    		},
    		function(response) {
    			alert("error create");
            	console.log(response);
    		}

    	)
    };

	StockService.getByOwner(login).then(
  		function(response) {
			$scope.stocks = response;
			console.log($scope.stocks.data);
		},
		function(response) {
			alert("error");
        	console.log(response);
		}


	);

  	$scope.delete = function(idStock) {
  		StockService.delete(idStock).then(
		function(response) {
			alert("DELETE type");
		},
		function(response) {
			alert("error delete");
        	console.log(response);
		}
		)
  	};

}]);