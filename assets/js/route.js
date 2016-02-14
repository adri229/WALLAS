var app = angular.module('wallas', ['ngRoute']);

app.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.when('/login', {
            pageTitle: 'Sign In - WALLAS',
            templateUrl: "assets/html/login.html",
            controller: 'LoginController'
        });

        $routeProvider.when('/register', {
            pageTitle: 'Register - WALLAS',
            templateUrl: 'assets/html/register.html',
            controller: 'RegisterController'
        });


        $routeProvider.otherwise({
            redirectTo: '/'
        });

    }]);

app.controller('LoginController', ['$scope', function ($scope) {
        $scope.mensaje = "Texto cargado desde el controlador Pagina1Controller";
    }]);

app.controller("RegisterController", ["$scope", function ($scope) {
        $scope.mensaje = "Texto cargado desde el controlador Pagina2Controller";
    }]);
