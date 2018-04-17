/**
 * Created by PhpStorm.
 * User: hzhuangxianan
 * Date: 2016/11/15
 * Time: 13:43
 */
<div id="noticePanel" class="btn-group">
    <button class="btn btn-notice alert-notice" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-commenting"></i>
    </button>
    <div id="noticeDropdown" class="dropdown-menu dm-notice pull-right">
        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li class="active"><a data-target="#notification" data-toggle="tab">消息（2）</a></li>
                <li><a data-target="#reminders" data-toggle="tab">提醒（4）</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="notification">
                    <ul class="list-group notice-list">
                        <li class="list-group-item unread">
                            <div class="row">
                                <div class="col-xs-2">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="col-xs-10">
                                    <h5><a href="#">消息来自某好友</a></h5>
                                    <small>2015-12-27</small>
                                    <span>这是是摘要...</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a class="btn-more" href="">查看全部消息 <i class="fa fa-long-arrow-right"></i></a>
                </div><!-- tab-pane -->

                <div role="tabpanel" class="tab-pane" id="reminders">
                    <h1 id="todayDay" class="today-day"></h1>
                    <h3 id="todayDate" class="today-date"></h3>
                    <h4 class="panel-title">即将到期</h4>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-xs-2">
                                    <h4>20</h4>
                                    <p>Aug</p>
                                </div>
                                <div class="col-xs-10">
                                    <h5><a href="">HTML5/CSS3 Live! United States</a></h5>
                                    <small>San Francisco, CA</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a class="btn-more" href="">查看更多提醒 <i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>