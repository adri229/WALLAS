'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('PositionController', ['$scope', '$cookies', 'PositionService',
    function($scope, $cookies, PositionService) {

        var user = $cookies.getObject('globals');
        var login = user.currentUser.login; 
        

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

        chartPositions(new Date(),new Date());

        $scope.changeChartPositions = function() {
            var startDate = $scope.yearsSince + '-' + $scope.monthsSince + '-01';
            var endDate = $scope.yearsAgo + '-' + $scope.monthsAgo + '-' + daysInMonth($scope.yearsAgo, $scope.monthsAgo); 
            chartPositions(startDate, endDate);
        }


        

        function daysInMonth(year, month) {
            return new Date(year, month, 0).getDate();
        }
    
    
   
    




}]); 









