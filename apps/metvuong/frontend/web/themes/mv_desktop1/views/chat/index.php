<?php
use yii\helpers\Url;
use frontend\models\Chat;

$params = [':jid' => Chat::find()->getJid(Yii::$app->user->identity->username)];
$jid_id = Yii::$app->dbChat->createCommand('SELECT jid_id FROM tig_ma_jids tmj WHERE jid=:jid')->bindValues($params)->queryOne();
if(!empty($jid_id)){
	$sql = 'SELECT tbl.* '.
		'FROM (SELECT owner_id, buddy_id, ts, body, direction, IF(owner_id = :jid_id, buddy_id, owner_id) AS withuser FROM tig_ma_msgs tmm WHERE :jid_id IN (owner_id, buddy_id) AND (owner_id != buddy_id) ORDER BY ts DESC) as tbl '.
		'GROUP BY tbl.withuser ORDER BY tbl.ts DESC';
	$msgs = Yii::$app->dbChat->createCommand($sql)->bindValues([':jid_id'=>$jid_id['jid_id']])->queryAll();
}
?>
<div class="title-fixed-wrap">
	<div class="container">
		<div class="chat-history">
			<div class="title-top">
				<span><?=Yii::t('chat', 'Chat history')?></span>
			</div>
			<div class="wrap-history clearfix">
				<div class="search-history">
					<input type="text" id="findConversation" class="form-control" placeholder="<?=Yii::t('general', 'Find by name...')?>">
					<button class="btn-search-hist" href="#"><span class="icon icon-search-small"></span></button>
				</div>
				<div class="chat-list clearfix">
					<?php
					if(!empty($msgs)) {
						foreach($msgs as $msg){
							$jid_user = Yii::$app->get('dbChat')->cache(function ($db) use ($msg) {
								return Yii::$app->get('dbChat')->createCommand('SELECT jid FROM tig_ma_jids tmj WHERE jid_id=:jid_id')->bindValues([':jid_id'=>$msg['withuser']])->queryOne();
							});
							if(!empty($jid_user['jid'])){
								$username = Chat::find()->getUsername($jid_user['jid']);
								$user = \frontend\models\User::find()->where(['username' => $username])->one();
							}
							if(!empty($user->profile)){
						?>
								<div class="item" chat-with="<?=$user->username;?>">
									<a href="<?= Url::to(['/chat/with', 'username' => $user->username]) ?>">
										<span class="wrap-img"><img src="<?=$user->profile->getAvatarUrl();?>" alt=""></span>
										<div class="chat-detail">
											<span class="pull-right time-chat"><?=date('H:i:s d-m-Y', strtotime($msg['ts']));?></span>
											<span class="name"><?=$user->profile->getDisplayName();?></span>
											<span><?=$msg['body'];?></span>
										</div>
									</a>
								</div>
						<?php
							}
						}
					}
					?>
				</div>
				<div class="chat-live">
					<div class="wrap-item-live clearfix">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script id="chat-receive-template" type="text/x-handlebars-template">
	<div class="item" chat-with="{{from}}">
		<a href="{{chatUrl}}">
			<span class="wrap-img"><img src="{{avatarUrl}}" alt=""></span>
			<div class="chat-detail">
				<span class="pull-right time-chat">{{time}}</span>
				<span class="name">{{fromName}}</span>
				<span>{{msg}}</span>
			</div>
		</a>
	</div>
</script>

<script>
	$(document).ready(function () {
		var timer = 0;
		$(document).bind('chat/withAnother', function (event, user) {
			if(user){
				$.ajax({
					type: "get",
					dataType: 'html',
					url: '/chat/with/'+user,
					success: function (data) {
						$('.wrap-item-live').html(data);
					}
				});
			}
		});
		$(document).on('click', '.wrap-history .chat-list .item a', function (e) {
			$('body').loading();
			var user = $(this).parent().attr('chat-with');
			$(document).trigger('chat/withAnother', [user]);
			Chat.historyMessage(user+'@<?=Chat::DOMAIN?>');
			return false;
		});

		/*$(document).on('keyup', '#findConversation', function (e) {
			var word = $(this).val();
			clearTimeout(timer);
			timer = setTimeout(function() {
				console.log(word);
				$.ajax({
					type: "post",
					dataType: 'json',
					url: '<?=Url::to(['/chat/conversation'])?>',
					data: {word: word},
					success: function (data) {
						console.log(data);
					}
				});
			}, 1000);
		});*/
		var row = $('.chat-list');
		$('#findConversation').keyup(function(){
			var key = $(this).val().toLowerCase();
			row.find('.item').each(function(){
				var self = $(this);
				if(self.text().toLowerCase().indexOf(key) == -1) {
					self.hide();
				} else {
					self.show();
				}
			});
		});
	});
</script>
