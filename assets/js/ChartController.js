'use strict';

var wallas = angular.module('wallasApp');

wallas.controller('ChartController', ['$scope', '$cookies', 'SpendingService', 'RevenueService',
	function($scope, $cookies, SpendingService, RevenueService) {

          
	var user = $cookies.getObject('globals');
    var login = user.currentUser.login;	

    var quantitySpendings = [];

    SpendingService.getByOwner(login).then(
        function(spendings) {
            RevenueService.getByOwner(login).then(
                function(revenues) {
                    console.log(spendings);
                    console.log(revenues);

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
    
   
    




}]); 




