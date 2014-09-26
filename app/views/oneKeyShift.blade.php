<!doctype html>
    <div class="datePick" ng-controller="Datepicker" >
        <label >查看从</label>
        <div class="datePicker">
            <p class="input-group">
                <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                       ng-model="checkStart" is-open="opened1" min-date="minDate" max-date="checkEnd"
                       datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                       ng-required="true " close-text="Close" ng-change="dateChange($index)"
                       ng-style="singleRoom.CheckInStyle"
                       ng-init="checkStart = toDay"/>

                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar"></i></button> <!-- open1($event) -->
                            </span>
            </p>
        </div>
        <label >到</label>
        <div class="datePicker">
            <p class="input-group">
                <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                       ng-model="checkEnd" is-open="opened2" min-date="checkStart" max-date="toDay"
                  datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                       ng-required="true" close-text="Close" ng-change="dateChange($index)"
                       ng-style="singleRoom.CheckOTStyle"
                       ng-init="checkEnd = toDay"
                    />

                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
            </p>
        </div>
    </div>

<div class="leftPart">
    <table>
        <tr>
            <th>条目</th>
            <th>合计</th>
        </tr>
        <tr>
            <td>现金<td>
            <td>{{cashSum}}<td>
        </tr>
        <tr>
            <td>房卡<td>
            <td>{{roomCardSum}}<td>
        </tr>
        <tr>
            <td>押金单<td>
            <td><td>
        </tr>

    </table>
    {{productSellSum}}
</div>
