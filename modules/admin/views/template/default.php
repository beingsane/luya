<div class="container-fluid m-t-1" ng-controller="DefaultController">
    <div class="row">

        <!-- sidebar -->
        <div class="col-md-4">
            <div class="list-group" ng-repeat="item in items" ng-class="{ 'm-t-2': !$first }">
                <div class="list-group-item bg-inverse">
                    {{item.name}}
                </div>
                <div class="list-group-item" ng-repeat="sub in item.items" ng-class="{'bg-primary' : sub.route == currentItem.route }" ng-click="click(sub)">
                    <i class="fa fa-{{ sub.icon }}"></i> {{ sub.alias }}
                </div>
            </div>
        </div>
        <!-- /sidebar -->

        <div class="col-md-8" ui-view>
            <div class="log">
                <div class="log__day" ng-repeat="item in dashboard" ng-controller="DashboardController" ng-init="logItemOpen = $first">
                    <div class="log__day-header">
                        <i class="material-icons">event</i>
                        <i class="log__day-toggler material-icons" ng-hide="logItemOpen" ng-click="logItemOpen = true">add</i>
                        <i class="log__day-toggler material-icons" ng-hide="!logItemOpen" ng-click="logItemOpen = false">remove</i>
                        <span>{{item.day * 1000 | date:"EEEE, dd.MM.yyyy"}}</span>
                    </div>

                    <div class="log__entries" ng-hide="!logItemOpen">

                        <div ng-repeat="(key, log) in item.items" ng-init="
                                        userChanged = item.items[key - 1] == null || (item.items[key - 1] != null && item.items[key - 1].name != log.name);
                                        iconChanged = item.items[key - 1] == null || (item.items[key - 1] != null && item.items[key - 1].icon != log.icon);
                                    ">
                            <div class="log__entry" style="z-index: {{item.items.length - key}}" ng-class="{ 'log__entry--first-of-group' : userChanged || iconChanged }">

                                <!-- Show if user or icon changed -->
                                <div class="log__entry-header" ng-show="userChanged || iconChanged">
                                    <i class="material-icons">{{log.icon}}</i>
                                </div>

                                <div class="log__entry-body">
                                    <small ng-show="userChanged || iconChanged" class="log__user">
                                        <i class="material-icons">person</i>
                                        {{ log.name }}
                                    </small>
                                    <p>
                                        <small class="log__time">{{ log.timestamp * 1000 | date:"HH:mm" }} Uhr</small>
                                                    <span class="log__info">
                                                        {{log.alias}}
                                                        <span ng-if="log.is_update == 1">bearbeitet.</span>
                                                        <span ng-if="log.is_insert == 1">hinzugefügt.</span>
                                                    </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>