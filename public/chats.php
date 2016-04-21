<?php

// composer autoload
include __DIR__ . '/../vendor/autoload.php';

$chats = getDb()->from('chat')
//->where('status', 'USER_WAITING')
->fetchAll();

foreach ($chats as $chat) {
	echo "<div>";
	echo "{$chat['name']} - {$chat['email']} - {$chat['telphone']}";
	if ($chat['status'] == 'CREATED') {
		echo  "<button class='operator-enter' type='button' data-chat='{$chat['id']}'>Atender</button>";
	}
	echo "</div>";
}