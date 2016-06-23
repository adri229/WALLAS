'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('DashboardController', ['$scope', '$cookies', 'PositionService', 'PercentSpendingService', 'SpendingService', 'RevenueService',
    function($scope, $cookies, PositionService, PercentSpendingService, SpendingService, RevenueService) {

        var user = $cookies.getObject('globals');
        var login = user.currentUser.login;

        $scope.hide;
        $scope.hideSpenRev = false;
        $scope.hidePositions = false;
        $scope.hidePercents = false;

        function notification() {
            $scope.hide = true;
            $scope.show = true;
        }

        function notificationSpenRev() {
            $scope.messageSpenRev = 'You do not have any revenues or spendings in this interval';
            $scope.hideSpenRev = true;
            $scope.showSpenRev = true;
        }

        function notificationPositions() {
        	$scope.messagePositions = 'You do not have any previous stocks before the interval selected. Please, creates a stock';
            $scope.hidePositions = true;
            $scope.showPositions = true;
        }

        function notificationPercents() {
        	$scope.messagePercents = 'You do not have any types assigned to spendings';
            $scope.hidePercents = true;
            $scope.showPercents = true;
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
                  
                var startDate = new Date($scope.yearFromSelect + '-' + $scope.monthFromSelect + '-01');
                var endDate = new Date($scope.yearToSelect + '-' + $scope.monthToSelect + '-' + daysInMonth($scope.yearToSelect, $scope.monthToSelect));
                var finalDate = new Date(endDate);
                var currentDate = new Date();

                if (finalDate < currentDate) {
                    chartSpenRev(startDate, endDate);
                    chartPositions(startDate, endDate);
                    chartPercents(startDate, endDate);
                } else {
                    notification();
                }


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
                    notificationSpenRev();
                }
            )
            },
            function(response) {
                $scope.chartConfigSR  = {};
                notificationSpenRev();
            }
             );

        }

        function chartPositions(startDate, endDate) {
            PositionService.getPositions(login, startDate, endDate).then(
                function(positions) {

                    var data = [];
                    positions.data.forEach(function(positionData) {
                        var date = new Date(positionData.date);
                        data.push([date.getTime(),positionData.total])
                    });


                    $scope.chartConfigPositions = {
                            options: {
                                chart: {
                                    type: 'column',
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
                                showInLegend: false,
                                name: 'Positions',
                                data: data,
                                color: '#FF8C00'
                            }],
                            title: {
                                text: 'Positions'
                            },
                            yAxis: {
                                title: {text: 'Total positions'}
                             },
                            xAxis: {
                                type: 'datetime',

                                dateTimeLabelFormats: {
                                    month: '%b'
                                },
                                title: {
                                    text: 'Positions'
                                }
                            },

                            loading: false
                        }
                },
                function(response) {
                    $scope.chartConfigPositions = {};
                    notificationPositions();
                }
            )

        }


        function chartPercents(startDate, endDate) {
        	PercentSpendingService.getPercents(login, startDate, endDate).then(
        		function(percents) {

        			var percent = [];
                    var categories = [];

                    var data = [];

					var sortedPercents = percents.data.sort(function(type1, type2){
						return type2["total"] - type1["total"];
					});

                    sortedPercents.forEach(function(typedata) {
						  categories.push(typedata["typename"]+' '+typedata["percent"]+'%');
                          data.push(typedata["total"]);
                    });


        			$scope.chartConfigPercents =  {
        				options: {
                            chart: {
                                type: 'bar',
                                backgroundColor: {
                                    linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                                    stops: [
                                        [0, 'rgb(255, 255, 255)'],
                                        [1, 'rgb(200, 200, 255)']
                                    ]
                                }
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: false
                            }
                        },
                        title: {
                            text: 'Positions'
                        },
						yAxis: {
							title: {text: 'Total amount'}
						},
						xAxis: {
                            categories: categories,
							title: {
							    text: 'Type of spending'
							}
						},
                        series: [{showInLegend: false, name: 'Total amount', data: data, color:'#C71585' }],
						loading: false,
        			}
        		},
        		function(response) {
                    $scope.chartConfigPercents = {};
                    notificationPercents();
                }
        	)
        }


        var defaultDate = new Date();

        var defaultStartDateUTC = new Date();
        defaultStartDateUTC.setDate(1);

        var defaultEndDateUTC = new Date();
        defaultEndDateUTC.setMonth(defaultEndDateUTC.getMonth()-1)
        defaultEndDateUTC.setDate(1);


        chartSpenRev(defaultStartDateUTC, defaultEndDateUTC);
        chartPositions(defaultStartDateUTC, defaultEndDateUTC);
        chartPercents(defaultStartDateUTC, defaultEndDateUTC);

        $scope.intervalDate = function(option) {
            var defaultDate = new Date();
            var defaultStartDateUTC = new Date();
            var defaultEndDateUTC = new Date();
            switch (option) {
                case 1:

                    defaultStartDateUTC.setDate(1);

                    defaultEndDateUTC.setMonth(defaultEndDateUTC.getMonth()+1);
                    defaultEndDateUTC.setDate(1);

                    break;
                case 2:

                    defaultStartDateUTC.setDate(1);
                    defaultStartDateUTC.setMonth(defaultStartDateUTC.getMonth()-2);

                    defaultEndDateUTC.setMonth(defaultEndDateUTC.getMonth()+1);
                    defaultEndDateUTC.setDate(1);

                    break;
                case 3:

                    defaultStartDateUTC.setFullYear(defaultStartDateUTC.getFullYear()-1);
                    defaultStartDateUTC.setDate(1);

                    defaultEndDateUTC.setMonth(defaultEndDateUTC.getMonth()+1);
                    defaultEndDateUTC.setDate(1);
                    break;
                default:
                    break;
            }
            chartSpenRev(defaultStartDateUTC, defaultEndDateUTC);
        	chartPositions(defaultStartDateUTC, defaultEndDateUTC);
        	chartPercents(defaultStartDateUTC, defaultEndDateUTC);
        }

        function defaultIntervalDate() {
            $scope.intervalDate(1);

        }
        defaultIntervalDate();




        $scope.closeAlert = function() {
            $scope.show = false;
        }

        $scope.closeAlertSpenRev = function() {
            $scope.showSpenRev = false;
        };
        $scope.closeAlertPositions = function() {
            $scope.showPositions= false;
        };
        $scope.closeAlertPercents = function() {
            $scope.showPercents = false;
        };

}]);
