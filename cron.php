<?php 
require "kernel/config.php";
require "kernel/fonctions.php";
$db = mysql_connect($host, $user, $pass)  or die('Erreur de connexion '.mysql_error());
mysql_select_db($user,$db)  or die('Erreur de selection '.mysql_error());

$hour = date("H");
$day = date("D");

// Look if the newsletter has been posted
if ($_GET['code'] == 'monday' OR $_GET['code'] == 'tuesday')
{
	$query_monday = mysql_fetch_array(mysql_query("SELECT send_mail_monday FROM config WHERE id = '1'"));
	$send_monday = $query_monday['send_mail_monday'];
}
// Check if quotes have been posted
if ($_GET['code'] == 'quote' OR $_GET['code'] == 'resetquote')
{
	$compteur_quote_posted_today_query = mysql_fetch_array(mysql_query("SELECT compteur_quote_posted_today FROM config WHERE id = '1'"));
	$compteur_quote_posted_today = $compteur_quote_posted_today_query['compteur_quote_posted_today'];
}
// Post quotes
if ($_GET['code'] == 'quote')
{
	if ($hour >= 00 AND $hour <= 02)
	{
		if ($compteur_quote_posted_today == '0')
		{
			$update = mysql_query("UPDATE config SET compteur_quote_posted_today = '1' WHERE id = '1'");
			flush_quotes();
			email_birthday();
		}
	}
}
// Reset Quotes
if ($_GET['code'] == 'resetquote')
{
	if ($hour >= 21 AND $hour <= 22)
	{
		if ($compteur_quote_posted_today == '1')
		{
			$update = mysql_query("UPDATE config SET compteur_quote_posted_today = '0' WHERE id = '1'");
		}
	}
}
// Send the newsletter
if ($_GET['code'] == 'monday')
{

	if ($send_monday == "0" AND $day == "Mon") 
	{ // ENVOI DE MAIL LE LUNDI
		$message = ''.$top_mail.'';
		$message.= MailRandomQuote(15);
		$message.= ''.$end_mail.'';
		
		echo 'Envoi de la newsletter';
		
		$today = date("d/m/Y");
		$i = '0';
		$txt_file = 'Newsletter on '.$today.'\r\n\n';


		$query = mysql_query("SELECT email, code FROM newsletter");

		while ($donnees = mysql_fetch_array($query)) 
		{
			$email = $donnees['email'];
			$code = $donnees['code'];

			if ($domaine == 'kotado.fr')
			{
				$unsubscribe= '<br /><span style="font-size:80%">Cet email a �t� envoy� � votre adresse ('.$email.') car vous �tes inscrit � la newsletter. Si vous souhaitez vous d�sinscrire, cliquez sur <a href="http://kotado.fr/newsletter.php?action=unsubscribe&email='.$email.'&code='.$code.'" target="_blank">ce lien</a>.</span>.';
			}
			else
			{
				$unsubscribe = '<br /><span style="font-size:80%">This email was adressed to you ('.$email.') because you are subscribed to our newsletter. If you want to unsubscribe, please follow <a href="http://teen-quotes.com/newsletter.php?action=unsubscribe&email='.$email.'&code='.$code.'" target="_blank">this link</a>.</span>';
			}
			
			$mail = mail ($email, "Newsletter", $message.$unsubscribe, $headers);
			if ($mail)
			{
				$i++;
				$txt_file .= '#'.$i.' : '.$email.' - '.$code.''."\r";
			}
		}

		$monfichier = fopen('files/compteur_email_hebdomadaire.txt', 'r+'); // Ouverture du fichier
		fseek($monfichier, 0); // On remet le curseur au d�but du fichier
		fputs($monfichier, $txt_file); // On �crit le nouveau nombre de pages vues
		fclose($monfichier);
			
		$update = mysql_query("UPDATE config SET send_mail_monday='1' WHERE id = '1'");
		mail('antoine.augusti@gmail.com', 'Sent newsletter', '', $headers);
	}
	else
	{
		echo 'Newsletter already sent.<br/>';
	}
}
// Reset newsletter
elseif ($_GET['code'] == 'tuesday')
{
	if ($send_monday == "1" AND $day == "Tue") 
	{ 	
		// RESET COMPTEUR MARDI
		$update = mysql_query("UPDATE config SET send_mail_monday = '0' WHERE id = '1'");
		mail('antoine.augusti@gmail.com', 'Reset done', '',$headers);
	}
	else
	{
		echo 'No reset.<br/>';
	}
}

echo 'Hello World.';