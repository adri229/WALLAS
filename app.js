'use strict';

var myapp = angular.module('myapp', ["highcharts-ng"]);

myapp.controller('myctrl', function ($scope, $http) {


	$http.get('rest/spendings/adri229?startDate=2016-02-10&endDate=2016-05-13').then(function(spendings) {
		$http.get('rest/revenues/adri229?startDate=2016-02-10&endDate=2016-05-13').then(function(revenues) {
			var quantitySpendings = new Array();
			var quantityRevenues = new Array();
			

			for(var key in spendings.data) {
				if(spendings.data.hasOwnProperty(key)) {
					quantitySpendings.push(parseInt(spendings.data[key].quantity));
					//alert(key + " -> " + spendings.data[key].quantity);

				}
			}
			console.log(quantitySpendings);
			
			for(var key in revenues.data) {
				if(revenues.data.hasOwnProperty(key)) {
					quantityRevenues.push(parseInt(revenues.data[key].quantity));
//					alert(key + " -> " + data[key].quantity);
				}
			}
			

			$scope.chartConfig1 = {
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
			
		});
	});


	$http.get('rest/stocks/adri229?startDate=2016-02-10&endDate=2016-05-13').then(function(stocks){
		var quantityStocks = new Array();

		console.log(stocks.data);

		for(var key in stocks.data) {
				if(stocks.data.hasOwnProperty(key)) {
					quantityStocks.push(parseInt(stocks.data[key].total));
//					alert(key + " -> " + data[key].quantity);
				}
			}
			

			$scope.chartConfigStocks = {
					options: {
						chart: {
							type: 'bar'
						}
					},
					series: [{
						data: quantityStocks
					}],
					title: {
						text: 'Stocks'
					},

					loading: false
			}

	});

});