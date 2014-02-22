<div class="row-fluid" ng-controller="FBImagesCtrl" ng-show="images.length > 0">

    <div class="span12 widget">
        <div class="widget-header">
            <span class="title">Image list</span>
        </div>
        <div class="widget-content">

            <table class="table">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                <tr ng-repeat="image in images">
                    <td><img src="{{ image.url }}" style="max-width: 150px;" alt=""/></td>
                    <td>{{ image.type }}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="removeImage($index)">
                            <span class="glyphicon glyphicon-trash"></span> Remove from list
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    function FBImagesCtrl($scope, url, $http, Packages, Alert) {

        $scope.images = [];

        Packages.whenReady(function () {

            $http({

                url: url.serverPackage('images', 'all'),
                method: 'GET',
                params: Packages.getDataToSend('images')

            }).success(function (data) {

                $scope.images = data;

            }).error(function (data) {
                Alert.error('From images package', 'Something went wrong while trying to fetch the images for this element');
            });
        });

        $scope.removeImage = function(index) {

            $http({
                url: url.serverPackage('images', 'one/' + $scope.images[index].id),
                method: 'DELETE'
            }).success(function (data) {

                Alert.success('From images package', 'Image deleted successfully.');

                $scope.images.splice(index, 1);

            }).error(function (data) {
                Alert.error('From images package', 'Somethine went wrong while trying to delete this image.');
            });
        }
    }

</script>










