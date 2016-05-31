'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('PositionController', ['$scope', '$cookies', 'PositionService',
    function($scope, $cookies, PositionService) {

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
                chartPositions(startDate, endDate);
            }
        }
        

        function daysInMonth(year, month) {
            return new Date(year, month, 0).getDate();
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

                    console.log(total);
                    
                    $scope.chartConfigPositions = {
                            options: {
                                chart: {
                                    type: 'column'
                                }
                            },
                            series: [{
                                data: total
                            }],
                            title: {
                                text: 'Spendings/Revenues'
                            },
                            

                            loading: false
                        }
                },
                function(response) {
                    alert("error");
                }
            )
            
        }


        var defaultDate = new Date();
        var defaultStartDateUTC = defaultDate.getUTCFullYear() + '-' + defaultDate.getUTCMonth()+ '-01'; 
        var defaultEndDateUTC = defaultDate.getUTCFullYear() + '-' + (defaultDate.getUTCMonth()+1)+ '-01'; 

        chartPositions(defaultStartDateUTC, defaultEndDateUTC);


}]); 









