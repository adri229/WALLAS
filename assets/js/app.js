'use strict';

var wallas = angular.module('wallasApp', ['ngMaterial','ngRoute', 'ngAnimate', 'ngCookies', 'ngMessages','ui.bootstrap', 'highcharts-ng']);

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
            when('/user', {
                pageTitle: 'User - WALLAS',
                templateUrl: 'assets/html/user.html',
                controller: 'UserController'
            }).
            when('/revenue', {
            	pageTitle: 'Revenue - WALLAS',
            	templateUrl: 'assets/html/revenue.html',
            	controller: 'RevenueController'
            }).
            when('/type', {
            	pageTitle: 'Type - WALLAS',
            	templateUrl: 'assets/html/type.html',
            	controller: 'TypeController'
            }).
            when('/stock', {
            	pageTitle: 'STOCK - WALLAS',
            	templateUrl: 'assets/html/stock.html',
            	controller: 'StockController'
            }).
            when('/spending', {
            	pageTitle: 'Spending - WALLAS',
            	templateUrl: 'assets/html/spending.html',
            	controller: 'SpendingController'
            }).
            when('/home', {
                pageTitle: 'Home - WALLAS',
                templateUrl: 'assets/html/home.html',
                controller: 'ChartController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

wallas.run(['$rootScope', '$location', '$cookieStore', '$http',
    function ($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }
 
        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in
            if (
              $location.path() !== '/login' 
              && $location.path() !== '/register' 
              && !$rootScope.globals.currentUser) {
              
                $location.path('/login');
            }
        });
    }]);