<?php
use yii\web\View;
use yii\helpers\Url;
use frontend\models\Chat;
$username = Yii::$app->user->identity->username;
$script = "var xmpp_jid = '".$username."';var xmpp_dm = '".Chat::find()->getDomain()."';var xmpp_key = '".Chat::find()->getKey()."';";
Yii::$app->getView()->registerJs($script, View::POS_HEAD);

Yii::$app->getView()->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js', ['position'=>View::POS_BEGIN]);

Yii::$app->getView()->registerJsFile('/js/strophe.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.chatstates.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.disco.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.muc.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.ping.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.pubsub.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.register.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.roster.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/chat.ui.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/chat.js', ['position'=>View::POS_BEGIN]);

Yii::$app->getView()->registerCssFile('/css/chat.css');
?>

<div style="text-align: center;">
    <h1>Chat Feature</h1>
    <ul>
            <li>Chat with: <a class="chatNow" chat-to="quangvinhit2010" href="#">quangvinhit2010</a></li>
        <li>Chat with: <a class="chatNow" chat-to="superadmin" href="#">superadmin</a></li>
    </ul>
</div>










<script id="chat-send-template" type="text/x-handlebars-template">
    <div class="wrap-me chat-infor">
        <div class="avatar-chat pull-left"><a href="#"><img src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
        <div class="wrap-txt-chat pull-left">
            {{msg}}
        </div>
    </div>
</script>
<script id="chat-receive-template" type="text/x-handlebars-template">
    <div class="wrap-you chat-infor">
        <div class="avatar-chat pull-right"><a href="#"><img src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
        <div class="wrap-txt-chat pull-right">
            {{msg}}
        </div>
    </div>
</script>
<script id="chat-typing-template" type="text/x-handlebars-template">
    <div class="loading-chat">{{from}} is typing<span class="one">.</span><span class="two">.</span><span class="three">.</span></div>
</script>
<script id="chat-box-template" type="text/x-handlebars-template">
    <div class="chat-group" chat-from="{{from}}" chat-to="{{to}}">
        <div class="title-chat clearfix"><em class="fa fa-close pull-right"></em><em class="fa fa-comments"></em>{{to}}</div>
        <div class="wrap-chat clearfix">
        </div>
        <div class="type-input-chat"><input type="text" id="typingMsg" placeholder="Tin nhắn của bạn...">
            <button class="sm-chat"><em class="fa fa-location-arrow"></em></button>
        </div>
    </div>
</script>
<div id="chat-container">

</div>
<script>
    $(document).ready(function () {
        $(this).trigger('chat/connect');

        $(document).on('click', '.chatNow', function (e) {
            $(this).trigger('chat/showBoxChat', {from: xmpp_jid, to: $(this).attr('chat-to')});
        });
        $(document).on('click','.title-chat .fa-close', function () {
            $(this).parent().parent().hide();
        });
        $(document).on('click','.title-chat', function () {
            if ( $('.title-chat').hasClass('active') ) {
                $('.title-chat').parent().css('height','auto');
                $('.title-chat').removeClass('active');
            }else {
                $('.title-chat').parent().css('height','34px');
                $('.title-chat').addClass('active');
            }
        });
        var timer = 0;
        $(document).on('keyup', '.chat-group #typingMsg', function (e) {
            var key = e.which;
            var msg = $('#typingMsg').val();
            var to_jid = $('.chat-group').attr('chat-to')+ '@'+xmpp_dm;
            if(key == 13){
                Chat.sendMessage(to_jid , msg);
                $('#typingMsg').val('');
                return false;
            }else{
                Chat.sendChatState(to_jid, 'composing');
                clearTimeout(timer);
                timer = setTimeout(function() {
                }, 100);
                return false;
            }
        });
        $(document).on('click', '.chat-group .sm-chat', function (e) {
            $('#typingMsg').val('');
            return false;
        });
    });
</script>