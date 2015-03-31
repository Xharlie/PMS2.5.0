<div class="menu-content"  id="contentMenu">
    <table class="table table-striped">
        <tr>
            <th ng-repeat="header in headers">{{header}}</th>
        </tr>
        <tr ng-repeat="row in rows">
            <td ng-repeat="(key, value) in row"><label >{{value}}</label></td>
        </tr>
    </table>
</div>
