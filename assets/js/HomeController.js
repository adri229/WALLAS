'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('HomeController', ['$scope', '$cookies', 'PositionService', 'PercentSpendingService', 'SpendingService', 'RevenueService',
    function($scope, $cookies, PositionService, PercentSpendingService, SpendingService, RevenueService) {

        var user = $cookies.getObject('globals');
        var login = user.currentUser.login;


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
        		chartPositions(startDate, endDate);
        		chartPercents(startDate, endDate);

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

                        var quantitySpendings = [];
                        var quantityRevenues = [];

                        for(var key in spendings.data) {
                            if(spendings.data.hasOwnProperty(key)) {
                                quantitySpendings.push(parseInt(spendings.data[key].quantity));
                            }
                        }   

                        for(var key in revenues.data) {
                            if(revenues.data.hasOwnProperty(key)) {
                                quantityRevenues.push(parseInt(revenues.data[key].quantity));
                            }
                        }

                        $scope.chartConfigSR = {
                            options: {
                                chart: {
                                    type: 'line'
                                }
                            },
                            series: [{
                                name: 'Spendings',
                                data: quantitySpendings
                            },{
                                name: 'Revenues',
                                data: quantityRevenues
                            }],
                            title: {
                                text: 'Spendings/Revenues'
                            },
                            xAxis: {
                                type: 'datetime',
                                dateTimeLabelFormats: { // don't display the dummy year
                                    month: '%e. %b',
                                    year: '%b'
                                },
                                title: {
                                    text: 'Date'
                                }
                            },
                            

                            loading: false
                        }
                },
                function(response) {
                   
                }
            )       
                
            },
            function(response) {
      
            }
             );  

        }

        function chartPositions(startDate, endDate) {
            PositionService.getPositions(login, startDate, endDate).then(
                function(positions) {
                    console.log(positions.data);

                    var total = [];

                    for(var key in positions.data) {
                            if(positions.data.hasOwnProperty(key)) {
                                total.push(parseInt(positions.data[key].total));
                            

                            }
                        }   

                    
                    $scope.chartConfigPositions = {
                            options: {
                                chart: {
                                    type: 'column'
                                }
                            },
                            series: [{
                                name: 'Positions',
                                data: total
                            }],
                            title: {
                                text: 'Positions'
                            },
                            xAxis: {
                                type: 'datetime',
                                
                            },

                            loading: false
                        }
                },
                function(response) {
                  
                    
                }
            )
            
        }

        function chartPercents(startDate, endDate) {
        	PercentSpendingService.getPercents(login, startDate, endDate).then(
        		function(percents) {
        			console.log(percents);

        			var percent = [];

                    for(var key in percents.data) {
                            if(percents.data.hasOwnProperty(key)) {
                                percent.push(parseInt(percents.data[key].percent));
                            

                            }
                        }   

                   

        			$scope.chartConfigPercents =  {
        				options: {
                            chart: {
                                type: 'bar'
                            }
                        },
                        title: {
                            text: 'Percents'
                        },
                        series: [{
                            name: 'Percents',
                            data: percent
                        }],
                            
						loading: false,
/*
						xAxis: {
			                type: 'datetime',
			                tickInterval: 30 * 24 * 3600 * 1000,
			                min: startDate,
			                max: endDate,
			                labels: {
			                    rotation: 45,
			                    step: 1,
			                    style: {
			                        fontSize: '13px',
			                        fontFamily: 'Arial,sans-serif'
			                    }
			                },
			                dateTimeLabelFormats: { // don't display the dummy year
			                    month: '%b \'%y',
			                    year: '%Y'
			                }
			            }*/

        			}

        			
            
        		},
        		function(response) {
          
                    
                }
        	)
        }


        var defaultDate = new Date();
        var defaultStartDateUTC = defaultDate.getUTCFullYear() + '-' + defaultDate.getUTCMonth()+ '-01'; 
        var defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01'; 

        chartSpenRev(defaultStartDateUTC, defaultEndDateUTC);
        chartPositions(defaultStartDateUTC, defaultEndDateUTC);
        chartPercents(defaultStartDateUTC, defaultEndDateUTC);



}]); 









