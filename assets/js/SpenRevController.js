'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpenRevController', ['$scope', '$cookies', 'SpendingService', 'RevenueService',
	function($scope, $cookies, SpendingService, RevenueService) {

		var user = $cookies.getObject('globals');
        var login = user.currentUser.login;

        $scope.hide = false;

        function notification(msg, type) {
            $scope.message = msg;
            $scope.type = type;
            $scope.hide = true;
            $scope.show = true;
        }

        $scope.years = [
            {id: '2016', name: '2016'},
            {id: '2017', name: '2017'},
            {id: '2018', name: '2018'},
            {id: '2019', name: '2019'},
            {id: '2020', name: '2020'},
            {id: '2021', name: '2021'},
            {id: '2022', name: '2022'},
            {id: '2023', name: '2023'},
            {id: '2024', name: '2024'},
            {id: '2025', name: '2025'}
        ];

        $scope.months = [
            {id: '01', name: 'January'},
            {id: '02', name: 'February'},
            {id: '03', name: 'March'},
            {id: '04', name: 'April'},
            {id: '05', name: 'May'},
            {id: '06', name: 'June'},
            {id: '07', name: 'July'},
            {id: '08', name: 'August'},
            {id: '09', name: 'September'},
            {id: '10', name: 'October'},
            {id: '11', name: 'November'},
            {id: '12', name: 'December'}
        ];


        $scope.changeIntervalDate = function() {
            if ($scope.yearFromSelect != null && $scope.monthFromSelect != null &&
                $scope.yearToSelect != null && $scope.monthToSelect != null) {
                var startDate = $scope.yearFromSelect + '-' + $scope.monthFromSelect + '-01';
                var endDate = $scope.yearToSelect + '-' + $scope.monthToSelect + '-' + daysInMonth($scope.yearToSelect, $scope.monthToSelect);
                chartSpenRev(startDate, endDate);
            }
        }


        function daysInMonth(year, month) {
            return new Date(year, month, 0).getDate();
        }

        function chartSpenRev(startDate, endDate) {

            var quantitySpendings = [];
            SpendingService.getDataChartSpenRev(login,startDate,endDate).then(
                function(spendings) {
                RevenueService.getDataChartSpenRev(login, startDate, endDate).then(
                    function(revenues) {

                        var sumSpendingsArray = [];
                        var sumRevenuessArray = [];

                        var init = new Date(startDate);
                        var end = new Date(endDate);

                        var auxDate = new Date(startDate);

                    	var sumSpendings = 0;
                    	var sumRevenues = 0;

                        for (init; init <= end; init.setMonth(init.getMonth() + 1)) {
                            auxDate.setMonth(init.getMonth() + 1);

                            spendings.data.forEach(function(spendingsData) {
                                if (new Date(spendingsData.date) >= init && new Date(spendingsData.date) < auxDate) {
                                    sumSpendings += spendingsData.quantity;
                                }
                            });

                            revenues.data.forEach(function(revenuesData) {
                                if (new Date(revenuesData.date) >= init && new Date(revenuesData.date) < auxDate) {
                                    sumRevenues += revenuesData.quantity;
                                }
                            });
                            
                            sumSpendingsArray.push([init.getTime(),sumSpendings]);
                            sumRevenuessArray.push([init.getTime(),sumRevenues]);

                            sumSpendings = 0;
                            sumRevenues = 0;
                        }
                       
                        $scope.chartConfigSR = {
                            options: {
                                chart: {
                                    type: 'line',
                                    backgroundColor: {
                                        linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                                        stops: [
                                            [0, 'rgb(255, 255, 255)'],
                                            [1, 'rgb(200, 200, 255)']
                                        ]
                                    }
                                }
                            },
                            series: [{
                            	name: 'Spendings',
                                data: sumSpendingsArray,
                                color: '#FF0000'
                            },{
                            	name: 'Revenues',
                                data: sumRevenuessArray,
                                color: '#2EFE2E'
                            }],
                            title: {
                                text: 'Spendings vs Revenues'
                            },
                            xAxis: {
                                type: 'datetime',
                                color: '#000000',
                                dateTimeLabelFormats: {
                                    month: '%b',
                                    year: '%b'
                                },
                                title: {
                                    text: 'Spendings vs Revenues'
                                }
                            },
                            loading: false
                        }
                },
                function(response) {
                    $scope.chartConfigSR  = {};
                    notification("You don't have any revenues or spendings in this interval", "info");
                }
            )
            },
            function(response) {
                $scope.chartConfigSR  = {};
                notification("You don't have any revenues or spendings in this interval", "info");
            }
             );

        }







        $scope.show = true;

        $scope.closeAlert = function(index) {
            $scope.show = false;
        };


        $scope.intervalDate = function(option) {
        	var defaultDate = new Date();
        	var defaultStartDateUTC = 0;
        	var defaultEndDateUTC = 0;
        	switch (option) {
        		case 1:
        			defaultStartDateUTC = defaultDate.getUTCFullYear() + '-' + defaultDate.getUTCMonth()+ '-01';
        			defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01';
        			break;
        		case 2:
        			defaultStartDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()-2)+ '-01';
        			defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01';
        			break;
        		case 3:
        			defaultStartDateUTC = (defaultDate.getUTCFullYear()-1) + '-' + defaultDate.getUTCMonth()+ '-01';
        			defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01';
        			break;
        		default:
        			break;
        	}

        	chartSpenRev(defaultStartDateUTC, defaultEndDateUTC);
        }

        function defaultIntervalDate() {
        	$scope.intervalDate(1);

        }
        defaultIntervalDate();

}]);
