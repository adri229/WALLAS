'use strict';

var wallas = angular.module('wallasApp');

wallas.factory('TypeService', ['$http', function($http) {

	var typeService = {};

	typeService.create = function(type) {
		return $http.post('rest/types', {
			name: type.name
		});
	}

	typeService.update = function(type,idType) {
		return $http.put('rest/types/'.concat(idType), {name: type.name});
	}


	typeService.getByOwner = function(login) {
		return $http.get('rest/types/'.concat(login));
	};

	typeService.delete = function(id) {
		return $http.delete('rest/types/' + id);
	};

	return typeService;

}]);