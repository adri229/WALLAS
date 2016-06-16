'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('PositionController', ['$scope', '$cookies', 'PositionService',
    function($scope, $cookies, PositionService) {

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
                chartPositions(startDate, endDate);
            }
        }
        

        function daysInMonth(year, month) {
            return new Date(year, month, 0).getDate();
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
                    notification("You don't have any previous stocks before the interval selected. Please, creates a stock", "info");    
                }
            )
            
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
            
            chartPositions(defaultStartDateUTC, defaultEndDateUTC);
        }

        function defaultIntervalDate() {
            $scope.intervalDate(1);

        }
        defaultIntervalDate();

}]); 









