<?php
/*
 * DEFINE TOKEN E API
 */
define('BOT_TOKEN', '312264482:AAFF3tzO-kXpvviO1m4BnFixKv-_RMxewig');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

function processMessage($message) {
  // processa a mensagem recebida
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    
    $text = $message['text'];//texto recebido na mensagem
    if (strpos($text, "/start") === 0) {
		//envia a mensagem ao usuário
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Olá, '. $message['from']['first_name'].
		'! Eu sou um bot que informa o resultado do último sorteio da Mega Sena. Será que você ganhou dessa vez? Para começar, escolha qual loteria você deseja ver o resultado', 'reply_markup' => array());
    } 
  } else {
    sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Desculpe, mas só compreendo mensagens em texto'));
  }
}
function sendMessage($method, $parameters) {
  $options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode($parameters),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
    )
);
$context  = stream_context_create( $options );
file_get_contents(API_URL.$method, false, $context );
}
//obtém as atualizações do bot
$update_response = file_get_contents(API_URL."getupdates");
$response = json_decode($update_response, true);
$length = count($response["result"]);
//obtém a última atualização recebida pelo bot
$update = $response["result"][$length-1];
if (isset($update["message"])) {
  processMessage($update["message"]);
}
?>
