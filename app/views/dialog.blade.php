
<div>
    <h3>{{content}}</h3>
    <button ng-style="dialogType" class="btn btn-warning pull-right" ng-click="cancel()">取消</button>
    <button class="btn btn-primary pull-right" ng-click="ok()">确认</button>
</div>


<script type="text/ng-template" id="window">
    <div tabindex="-1" role="dialog" class="modal fade ng-isolate-scope in" ng-class="{in: animate}"
         ng-style="{'z-index': 1050 + index*10, display: 'block'}"
         ng-click="close($event)"  index="0" animate="animate" style="z-index: 1050; display: block;">
        <div class="modal-dialog" ng-class="{'modal-sm': size == 'sm', 'modal-lg': size == 'lg'}">
            <div class="modal-confirm" ng-transclude="">
            </div>
        </div>
    </div>
</script>



