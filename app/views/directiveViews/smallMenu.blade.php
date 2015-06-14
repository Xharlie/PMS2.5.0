

<div class="menu-small"  id="smallMenu">
    <!--  在这个div container 内可随意修改,div 外不可加内容包括comment,会被认为是dom sub root.
     另外 id smallMenu不可删去，用作destroy identifier-->
    <ul>
        <li ng-repeat="action in iconNAction">
            <a ng-click="excAction(action.action)" class="btn">
                <!-- icon class 在 public/js/Angular/module_frontDesk/pan_lib/util.js中 变量avaIconAction里 -->
                <span ng-class="action.icon"></span> {{ action.action }}
            </a>
        </li>
    </ul>
</div>