'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('SpenRevController', ['$scope', '$cookies', 'SpendingService', 'RevenueService',
	function($scope, $cookies, SpendingService, RevenueService) {

		var user = $cookies.getObject('globals');
        var login = user.currentUser.login; 
        


        function chartSpenRev(initDate, endDate) {
            
            
            var quantitySpendings = [];
            SpendingService.getDataChartSpenRev(login,initDate,endDate).then(
                function(spendings) {
                RevenueService.getDataChartSpenRev(login, initDate, endDate).then(
                    function(revenues) {

                        var quantitySpendings = [];
                        var quantityRevenues = [];

                        for(var key in spendings.data) {
                            if(spendings.data.hasOwnProperty(key)) {
                                quantitySpendings.push(parseInt(spendings.data[key].quantity));
                                //alert(key + " -> " + spendings.data[key].quantity);

                            }
                        }   

                        for(var key in revenues.data) {
                            if(revenues.data.hasOwnProperty(key)) {
                                quantityRevenues.push(parseInt(revenues.data[key].quantity));
            //                  alert(key + " -> " + data[key].quantity);
                            }
                        }

                        $scope.chartConfigSR = {
                            options: {
                                chart: {
                                    type: 'line'
                                }
                            },
                            series: [{
                                data: quantitySpendings
                            },{
                                data: quantityRevenues
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
                
            },
            function(response) {
                alert("error");
                console.log(response);
            }
             );  

        }

        chartSpenRev(new Date(),new Date());

        $scope.changeChartSpenRev = function() {

        	var startDate = $scope.yearsSince + '-' + $scope.monthsSince + '-01';
        	var endDate = $scope.yearsAgo + '-' + $scope.monthsAgo + '-' + daysInMonth($scope.yearsAgo, $scope.monthsAgo); 
            chartSpenRev(startDate, endDate);
        }


        

        function daysInMonth(year, month) {
            return new Date(year, month, 0).getDate();
        }
    
    
   
    




}]); 




