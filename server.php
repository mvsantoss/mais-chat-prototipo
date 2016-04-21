<?php

// composer autoload
include __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Autoloader;
use PHPSocketIO\SocketIO;

function getDb() {
	global $db;
	if (isset($db)) {
		return $db;
	}
	$pdo = new PDO( 'sqlite:' . __DIR__ . '/db.sqlite'); 
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$fpdo = new FluentPDO($pdo);
	$fpdo->debug = true;
	return $db = $fpdo;
}


$io = new SocketIO(2020);
$io->on('connection', function($socket) use ($io) {
  	$socket->on('user enter', function($data) use ($socket) {
	  	$name = $data['name'];
		$email = $data['email'];
		$telphone = $data['telphone'];
		$status = 'CREATED';
		$created_at = time();
		$channel = $socket->id;
		
		$chat_id = $socket->chat_id = getDb()->insertInto('chat', compact('name', 'email', 'telphone', 'created_at', 'channel', 'status'))->execute();
  	});

  	$socket->on('operator enter', function($data) use ($socket) {
		$chat_id = $socket->chat_id = $data['chat'];
	  	$operator = $data['operator']; 
	  	$op_name = $operator['name'];
	  	$op_email = $operator['email'];
	  	$op_telphone = $operator['telphone'];
	  	$started_at = time();
		$status = 'STARTED';
		
		getDb()->update('chat', compact('op_name', 'op_email', 'op_telphone', 'started_at', 'status'), $chat_id)->execute();
		$channel = getDb()->from('chat', $chat_id)->fetch('channel');

	    $socket->join($channel);
	    $socket->to($channel)->emit('start', compact('operator'));
  	});

  	$socket->on('user message', function($message) use ($io, $socket) {
		$chat_id = $socket->chat_id;
		$source = 'USER';
		$created_at = time();
		getDb()->insertInto('chat_messages', compact('chat_id', 'source', 'message', 'created_at'))->execute();

		$name = getDb()->from('chat', $chat_id)->fetch('name');
		$channel = getDb()->from('chat', $chat_id)->fetch('channel');
	    $io->to($channel)->emit('message', compact('message', 'name'));
  	});

  	$socket->on('operator message', function($message) use ($io, $socket) {
		$chat_id = $socket->chat_id;
		$source = 'OPERATOR';
		$created_at = time();
		getDb()->insertInto('chat_messages', compact('chat_id', 'source', 'message', 'created_at'))->execute();

		$name = getDb()->from('chat', $chat_id)->fetch('op_name');
		$channel = getDb()->from('chat', $chat_id)->fetch('channel');
	    $io->to($channel)->emit('message', compact('message', 'name'));
  	});

  	$socket->on('disconnect', function () use($socket) {
        $chat_id = $socket->chat_id;
        $status = 'FINISHED';
        $finished_at = time();
        getDb()->update('chat', compact('status', 'finished_at'), $chat_id)->execute();
        
        $channel = getDb()->from('chat', $chat_id)->fetch('channel');
        $socket->to($channel)->emit('finish');
   });
});

$web = new WebServer('http://0.0.0.0:8080');
$web->addRoot('localhost', __DIR__ . '/public');

getDb()->getPdo()->exec(file_get_contents(__DIR__ . '/db.sql'));
Worker::runAll();