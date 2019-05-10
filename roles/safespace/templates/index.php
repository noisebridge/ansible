<!DOCTYPE html>
{{ ansible_managed | comment('xml') }}
<!--
<?php

function file_post_contents($url, $data, $username = null, $password = null) // from https://stackoverflow.com/questions/11319520/php-posting-json-via-file-get-contents
{
    $postdata = http_build_query($data);
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    if($username && $password)
    {
        $opts['http']['header'] = ("Authorization: Basic " . base64_encode("$username:$password"));
    }
    $context = stream_context_create($opts);
    return file_get_contents($url, false, $context);
}


// payload={\"channel\": \"#hook-testing\", \"username\": \"webhookbot\", \"text\": \"This is posted to #hook-testing and comes from a bot named webhookbot.\", \"icon_emoji\": \":ghost:\"}"
define('WEBHOOK_URL', 'https:/' . '/hooks.slack.com/services/{{ safespace_slack_token }}');
define('SLACK_CHANNEL', '{{ safespace_slack_channel }}');
define('SLACK_USERNAME', '{{ safespace_slack_username }}');
define('SLACK_ICON', ':heart:');
function send_msg($msg) {
	$replaces = 1;
	while ($replaces) {
		$msg = str_replace(["@channel", "@everyone"], "-AT-", $msg, $replaces);
	}
	$payload = json_encode(array(
		'channel' => SLACK_CHANNEL,
		'username' => SLACK_USERNAME,
		'text' => $msg . "_(This message was filled out on safespace.noisebridge.net by someone who may not have slack access and who may wish to remain anonymous)._",
		'icon_emoji' => SLACK_ICON
	));
	$ret="";
	try {
		$ret = file_post_contents(WEBHOOK_URL, array('payload' => $payload));
	} catch(Exception $e) {
		echo($e . "\n");
		return false;
	}
	return $ret === "ok";
}

function p($n) {
	return (isset($_POST[$n]) && is_string($_POST[$n])) ? "$n: " . $_POST[$n] . "\n" : "";
}

$prepend = "";
if (isset($_POST["message"]) && is_string($_POST["message"])) {
	if(print_r(send_msg(p("name") . p("contact") . $_POST["message"]))) {
		$prepend = "Message Sent.";
	} else {
		$prepend = "We encountered an error while trying to send your message.  If you see this, it would be appreciated if you contacted Roy (@rizend on slack or horsy4nbs.7.pcao@spamgourmet.com) so they can try and fix the issue.";
	}
}

?>
-->
<html>
<head>
	<title>Safespace Reporting Tool</title>
	<style>
input[type=text] {
	margin-left: 3em;
}
textarea {
	width: 75%;
}
	</style>
</head>
<body>
	<h2>Noisebridge safe space reporting tool</h2>
	<p>
		Please be aware that this form sends a message to the #space-guardians channel on the noisebridge slack which is viewable by anyone on our slack.
	</p>
	<hr/>
	<i><?php echo($prepend); ?></i>
	<form method="post">
		Name (optional):<br/>
			<input type="text" name="name" placeholder="Kate Libby"></input><br/>
		Contact Info (optional):<br/>
			<input type="text" name="contact" placeholder="ac1d.burn@protonmail.ch"></input><br/><br/>
		<textarea name="message" placeholder="I'm having trouble leaving a conversation in front of the noise-square table.  Could someone intervene so I can leave?"></textarea><br/><br/>
		<input type="submit" value="Send Message"></input>
	</form>
</body>
</html>
