'use strict';

var wallas = angular.module('wallasApp', ['ngRoute', 'ngCookies']);

wallas.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.
            when('/login', {
                pageTitle: 'Sign In - WALLAS',
                templateUrl: "../html/login.html",
                controller: 'LoginController'
            }).
            when('/register', {
                pageTitle: 'Register - WALLAS',
                templateUrl: '../html/register.html',
                controller: 'RegisterController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);
