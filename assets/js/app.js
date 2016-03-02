'use strict';

var wallas = angular.module('wallasApp', ['ngRoute', 'ngCookies', 'ui.bootstrap']);

wallas.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.
            when('/login', {
                pageTitle: 'Sign In - WALLAS',
                templateUrl: "assets/html/login.html",
                controller: 'AuthenticationController'
            }).
            when('/register', {
                pageTitle: 'Register - WALLAS',
                templateUrl: 'assets/html/register.html',
                controller: 'RegisterController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);
/*
wallas.constant('AUTH_EVENTS' {
	loginSuccess : 'auth-login-success',
    loginFailed : 'auth-login-failed',
    logoutSuccess : 'auth-logout-success',
    sessionTimeout : 'auth-session-timeout',
    notAuthenticated : 'auth-not-authenticated',
    notAuthorized : 'auth-not-authorized'


})


*/