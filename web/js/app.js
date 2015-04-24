var myApp = angular.module('kebhub', []);

myApp.config( function($interpolateProvider) {
  $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});



// Index Controller
myApp.controller('dashboardCtrl', function($scope, $http) {
	// Get url from his name.
  var getRecordedPostUrl            = Routing.generate('getter_recorded');
  var deleteRecordedPostUrl         = Routing.generate('getter_delete_recorded');
  var deleteMultipleRecordedPostUrl = Routing.generate('getter_delete_multiple_recorded');
  var addRecordPostUrl              = Routing.generate('getter_add_record');
  var addMultipleRecordPostUrl      = Routing.generate('getter_add_multiple');

  $scope.recorded = {};
  $scope.multipleItems = [];
  $scope.status = "recorded";
  $scope.noPost = false;


  // Initialisation
  $scope.init = function() {
    $http({
      method: 'GET',
      url: getRecordedPostUrl
    })
    .success(function (data, status, headers, config) {
      console.log(data);
        $scope.recorded = data;
        $scope.hideLoading();
    })
    .error(function (data, status, headers, config) {
      // $scope.collections = 'error';
    });
  }

  $scope.getDistantPosts = function(social) {
    var streamPostUrl = Routing.generate('getter_stream', {'filter' : social});
    $http({
      method: 'GET',
      url: streamPostUrl
    })
    .success(function (data, status, headers, config) {
        $scope.hideLoading();
        console.log(data[social]);
        if (typeof data[social] !== "undefined") {
          data[social].unset(data[social][0]);
          $scope.recorded = data[social];
          console.log($scope.recorded);
        }
        else {
          $scope.recorded = [];
          $scope.noPost = true;
        }
    })
    .error(function (data, status, headers, config) {
      // $scope.collections = 'error';
    });
  }


  // Delete posts recorded function
  $scope.deleteRecord = function(id) {

    if (typeof id == "number") {

      $scope.showLoading();

      $http({
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: deleteRecordedPostUrl,
        data: {'id' : id}
      })
      .success( function (data, status, headers, config) {
        $scope.hideLoading();
        swal("Good job!", "Deleted!", "success");
        $scope.recorded = data;
      });

    }

    else if (id === "multiple") {
      var ids = [];

      for (var i=0; i < $scope.multipleItems.length; i++) {
        id = $scope.multipleItems[i];
        ids.push($scope.recorded[id]['id']);
      }

      console.log(ids);
      // return false;

      $scope.showLoading();

      $http({
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: deleteMultipleRecordedPostUrl,
        data: {'id' : ids}
      })
      .success( function (data, status, headers, config) {
        // $scope.hideLoading();
        $scope.multipleItems = [];
        $scope.init();
      });

    }

    return false;
  };

  // Add posts to record database
  $scope.addRecord = function(event, key) {

    if (typeof key == "number") {
      if (typeof $scope.recorded[key] != "undefined") {

        $scope.recorded[key]['type'] = $scope.status;
        $scope.recorded[key]['date'] = "";
        
        if ($scope.status == "twitter") {
          $scope.recorded[key]['picture'] = "";
          $scope.recorded[key]['title'] = "";
        }

        if ($scope.status == "instagram") {
          $scope.recorded[key]['text'] = "";
        }
        
        $http({
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          url: addRecordPostUrl,
          data: $scope.recorded[key]
        })
        .success( function (data, status, headers, config) {
          $scope.hideLoading();
          $(event.target).parents('.list-group-item').hide().next('.list-group-separator').hide();
        });

      }

    }
    else if (key === "multiple") {

      var toInsert = [];

      for (var i=0; i < $scope.multipleItems.length; i++) {
        id = $scope.multipleItems[i];

        $scope.recorded[id]['type'] = $scope.status;
        $scope.recorded[id]['date'] = "";
        
        if ($scope.status == "twitter") {
          $scope.recorded[id]['picture'] = "";
          $scope.recorded[id]['title'] = "";
        }

        if ($scope.status == "instagram") {
          $scope.recorded[id]['text'] = "";
        }

        toInsert.push($scope.recorded[id]);

      }

      $http({
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: addMultipleRecordPostUrl,
        data: toInsert
      })
      .success( function (data, status, headers, config) {
        $scope.showLoading();
        $scope.getDistantPosts($scope.status);
        $scope.multipleItems = {};      
      });

    }

    return false;
  }


  // Add multiple item to delete/add function
  $scope.multiple = function(event, key) {
    
    if ($scope.multipleItems.indexOf(key) == -1) {
      $scope.multipleItems.push(key);
    }
    else {
      $scope.multipleItems.unset(key);
    }

  }


  // Change category
  $scope.changeCategory = function(cat, event) {

    if (cat == "recorded") {
      $scope.status = "recorded";
      $scope.init();
    }
    else if (cat == "twitter") {
      $scope.status = "twitter";
      $scope.getDistantPosts("twitter");
    }
    else if (cat == "instagram") {
      $scope.status = "instagram";
      $scope.getDistantPosts("instagram");
    }

    $scope.multipleItems = [];
    $scope.showLoading();
    $('.active').removeClass('active');
    $(event.target).parent().addClass('active');
    $scope.noPost = false;
    return false;

  }

  $scope.showLoading = function() {
    $('.content-records').hide()
    $scope.loading = true;
  };

  $scope.hideLoading = function() {
    $('.content-records').show()
    $scope.loading = false;
  };
  
  $scope.init();

});

Array.prototype.unset = function(val){
    var index = this.indexOf(val)
    if(index > -1){
        this.splice(index,1)
    }
}