<div class="pop pop-menu"  id="smallMenu">
    <ul>
        <li ng-repeat="action in iconNAction">
            <a ng-click="excAction(action.action)" class="btn">
                <span ng-class="action.icon"></span> {{ action.action }}
            </a>
        </li>
    </ul>
</div>