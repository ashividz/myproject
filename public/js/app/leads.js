var app = angular.module('nutri1', []);

app.controller('leadsController', function($scope, $http){

	getLeadDetails();

	function getLeadDetails() {
        var params = {};
        return $http.get('leads/json', {params: params} )
        
        .success(function(response) {
          console.log(response)
        });
    };
});