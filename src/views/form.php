<div class="row-fluid" ng-controller="FBImagesCtrl" ng-show="uploader">

    <div class="span12 widget">
        <div class="widget-header">
            <span class="title">Uploading images <small>(Drag and drop is supported)</small></span>
        </div>
        <div class="widget-content" ng-file-drop>

            <!-- 2. ng-file-select | ng-file-select="options" -->
            <input ng-file-select type="file" multiple/>

            <table class="table" style="margin-top:10px; border-top:1px solid #666;"
                   ng-show="uploader.queue.length > 0">
                <thead>
                <tr>
                    <th width="20%">Name</th>
                    <th width="25%">Type</th>
                    <th ng-show="uploader.isHTML5">Size</th>
                    <th ng-show="uploader.isHTML5">Progress</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="item in uploader.queue" ng-init="item.formData[0].options_image_type = options.types[0]">
                    <td>
                        <strong>{{ item.file.name }}</strong>
                        <div ng-show="uploader.isHTML5" ng-thumb="{ file: item.file, height: 50 }"></div>
                    </td>
                    <td>
                        <div class="control-group">
                            <div class="controls">
                                <select ng-model="item.formData[0].options_image_type">
                                    <option value="{{ type }}" ng-repeat="type in options.types">
                                        {{ type }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td ng-show="uploader.isHTML5" nowrap>{{ item.file.size/1024/1024|number:2 }} MB</td>
                    <td ng-show="uploader.isHTML5">
                        <div class="progress" style="margin-bottom: 0;">
                            <div class="progress-bar" role="progressbar"
                                 style="background:#428BCA; height:60px; width:0%"
                                 ng-style="{ 'width': item.progress + '%' }"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                        <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                        <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                    </td>
                    <td nowrap>
                        <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()"
                                ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                            <span class="glyphicon glyphicon-upload"></span> Upload
                        </button>
                        <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()"
                                ng-disabled="!item.isUploading">
                            <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                            <span class="glyphicon glyphicon-trash"></span> Remove from list
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div ng-show="uploader.queue.length > 0">
                <p>
                    Queue progress:

                <div class="progress" style="">
                    <div class="progress-bar" role="progressbar"
                         style="background:#428BCA; height:60px; width:0%"
                         ng-style="{ 'width': uploader.progress + '%' }"></div>
                </div>
                </p>
                <button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll()"
                        ng-disabled="!uploader.getNotUploadedItems().length">
                    <span class="glyphicon glyphicon-upload"></span> Upload all
                </button>
                <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()"
                        ng-disabled="!uploader.isUploading">
                    <span class="glyphicon glyphicon-ban-circle"></span> Cancel all
                </button>
                <button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()"
                        ng-disabled="!uploader.queue.length">
                    <span class="glyphicon glyphicon-trash"></span> Remove all from list
                </button>
            </div>

        </div>
    </div>

</div>


<script type="text/javascript">

    function FBImagesCtrl($scope, Packages, $fileUploader, url) {

        Packages.whenReady(function () {

            $scope.options = Packages.mergeOptions('images', {
                maximum: 20,
                types: ['main'],
                group :'',
                image_name: '',
                image_title: '',
                image_alt: ''
            });

            var dataToSend = Packages.getDataToSend('images', true);

            // Creates uploader
            var uploader = $scope.uploader = $fileUploader.create({
                scope: $scope,
                url: url.serverPackage('images', 'upload'),
                formData: [dataToSend]
            });


            uploader.bind('progress', function (event, item, progress) {
                console.info('Progress: ' + progress, item);
            });
            // Images only
            uploader.filters.push(function (item /*{File|HTMLInputElement}*/) {
                var type = uploader.isHTML5 ? item.type : '/' + item.value.slice(item.value.lastIndexOf('.') + 1);
                type = '|' + type.toLowerCase().slice(type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            });
        });
    }

</script>