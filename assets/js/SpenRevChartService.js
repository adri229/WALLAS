'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('SpenRevChartService', ['$http', function($http) {

	var chartService = {};

	chartService.configChart = function(login, startDate, endDate) {
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
				
				return {
					spendings: quantitySpendings,
					revenues: quantityRevenues
				};
				
				
			});
	});
	};

}]);