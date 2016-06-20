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
            when('/spenrev', {
                pageTitle: 'Spendings-Revenues - WALLAS',
                templateUrl: 'assets/html/spenrev.html',
                controller: 'SpenRevController'
            }).
            when('/positions', {
                pageTitle: 'Positions - WALLAS',
                templateUrl: 'assets/html/positions.html',
                controller: 'PositionController'
            }).
            when('/percents', {
                pageTitle: 'Percents - WALLAS',
                templateUrl: 'assets/html/percents.html',
                controller: 'PercentSpendingController'
            }).
            when('/dashboard', {
                pageTitle: 'Home - WALLAS',
                templateUrl: 'assets/html/dashboard.html',
                controller: 'DashboardController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

wallas.run(['$rootScope', '$location', '$cookies', '$http',
    function ($rootScope, $location, $cookies, $http) {
        // keep user logged in after page refresh
        
        $rootScope.globals = $cookies.getObject('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata;
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