var app=angular.module('dep_deg_app',[]);

app.filter('titleCase',function(){
	return function(str){
		var substr=str.split(' ');
		var newstr='';
		for (s in substr)
		{
			newstr += substr[s].charAt(0).toUpperCase() + substr[s].substring(1).toLowerCase() + ' ';
		}
		return newstr;
	}
});

app.controller('dep_deg_ctrl',function($scope,$http,$log,$window){
	alert(angular.version.full);
	$scope.srchDep=function(){
		//var srch_depparam=$.param({depname:$scope.srch_dep});
		$http({
			method:'post',
			url:base_url + 'MASTERS/srchDep',
			headers:{'Content-Type':'application/x-www-form-urlencoded;charset=utf-8;'}
		}).then(function(response){
			$scope.dep_data=response.data;
			$scope.deplist=$scope.dep_data;
		},function responseError(){
			$scope.dep_data=response.statusText;
		});
	};

	$scope.srchDep();


	$scope.srchDesig=function(){
		$http({
			method:'post',
			url:base_url + 'MASTERS/srchDesig',
			headers:{'Content-Type':'application/x-www-form-urlencoded;charset=utf-8;'},			
		}).then(function(response){
			$scope.desig_data=response.data;
		},function responseError(){
			$scope.desig_data=response.statusText;
		});
	};
	$scope.srchDesig();

	
});