<?php 
include 'header.php'; 
include 'lang/'.$language.'/search.php';
include 'lang/'.$language.'/user.php';

$value_search = htmlspecialchars(mysql_real_escape_string($_GET['q']));
$country = htmlspecialchars(mysql_real_escape_string($_GET['country']));
$city = htmlspecialchars(mysql_real_escape_string($_GET['city']));
$logged = $_SESSION['logged'];

if (strlen($value_search) < 50)
{
	$query = mysql_query("INSERT INTO teen_quotes_search (text) VALUES ('".$value_search."') ON DUPLICATE KEY UPDATE value = value + 1");
}

if (empty($value_search) AND empty($city) AND empty($country)) 
{
	echo '
	<div class="post">
		<h1>'.$error.'</h1>
		'.$not_completed.'
	</div>';
}
else
{
	if (empty($city) AND empty($country))
	{

		//Recherche d'une quote
		if ($logged)
		{
			$sqlQuoteFav = ", (SELECT COUNT(*)
							FROM teen_quotes_favorite f
							WHERE q.id = f.id_quote AND f.id_user = '$id') AS is_favorite ";
		}
		else
		{
			$sqlQuoteFav = "";
		}

		$keywords = explode(" ", $value_search);
		$kwSql = "";

		foreach($keywords as $k)
		{
			$kwSql .= "AND q.texte_english LIKE '%$k%'";
		}

		$sqlQuote = "SELECT q.texte_english texte_english, q.id id, q.auteur_id auteur_id, q.date date, a.username auteur,
					(SELECT COUNT(*) FROM teen_quotes_comments c WHERE q.id = c.id_quote) AS nb_comments ".$sqlQuoteFav."
					 FROM teen_quotes_quotes q, teen_quotes_account a 
					 WHERE q.auteur_id = a.id AND q.approved = '1'
					 ".$kwSql." 
					 ORDER BY q.id DESC
					 LIMIT 0,15";

		$resultatsQuotes = mysql_query($sqlQuote);
		$num_rows_quote = mysql_num_rows($resultatsQuotes);

		//Recherche d'un membre
		$sqlMembre = "SELECT * FROM teen_quotes_account 
					  WHERE (username LIKE '%$value_search%' OR SOUNDEX(username) LIKE SOUNDEX('$value_search')) 
					  AND hide_profile = '0'
					  ORDER BY (CASE WHEN username = '$value_search' THEN 3 ELSE 0 END) + (CASE WHEN username LIKE '$value_search%' THEN 2 ELSE 0 END) + (CASE WHEN username LIKE '%$value_search%' THEN 1 ELSE 0 END) DESC
					  LIMIT 0,15";
					  
		$resultatsMembres = mysql_query($sqlMembre);
		$num_rows_members = mysql_num_rows($resultatsMembres);			
	}
	else
	{
		$num_rows_quote = 0;

		if (!empty($city) AND empty($country))
		{
			$num_rows_members = mysql_num_rows(mysql_query("SELECT id FROM teen_quotes_account WHERE city LIKE '%".$city."%' AND hide_profile = '0'"));
		}
		elseif (empty($city) AND !empty($country))
		{
			$num_rows_members = mysql_num_rows(mysql_query("SELECT id FROM teen_quotes_account WHERE country LIKE '%".$country."%' AND hide_profile = '0'"));	
		}
	}

	$num_rows_result = $num_rows_quote + $num_rows_members;

	if ($num_rows_quote >= 1 OR $num_rows_members >= 1) 
	{
		echo '
		<div class="post">
			<div class="grey_post">
				<h1><img src="http://'.$domain.'/images/icones/search_result.png" class="icone" />'.$search_results.'<span class="right" style="font-size:70%;padding-top:5px">'.$num_rows_result; ?> <?php echo $results; ?><?php if ($num_rows_result > 1){echo"s";}
				echo
				'</span></h1>';

				if ($num_rows_quote >= 1 AND $num_rows_members >= 1)
				{
					echo '<h3><img src="http://'.$domain.'/images/icones/profil.png" class="icone"><a href="#quotes">'.$quotes.'</a><span class="right"><img src="http://'.$domain.'/images/icones/staff.png" class="icone"><a href="#members">'.$members.'</span></a></h3>';
				}
			echo '</div>';
		echo '</div>';

		// RESULTAT DES QUOTES
		if ($num_rows_quote >= 1)
		{
			echo '
			<div class="post" id="quotes">
				<h2><img src="http://'.$domain.'/images/icones/profil.png" class="icone">'.$quotes.'<span class="right" style="font-size:90%;padding-top:5px">'.$num_rows_quote; echo ' '.$results; if ($num_rows_quote > 1){echo"s";} if ($num_rows_quote > 15){echo ' '.$max_result;} echo '</span></h2>';

				while ($result = mysql_fetch_array($resultatsQuotes))
				{
					displayQuote($result, NULL, NULL, 'search');
				}
			echo '</div>';
		}
		// RESULTAT DES MEMBRES
		if ($num_rows_members >= 1)
		{
			echo '
			<div class="post" id="members">
			<h2><img src="http://'.$domain.'/images/icones/staff.png" class="icone">'.$members.'<span class="right" style="font-size:90%;padding-top:5px">'.$num_rows_members; echo ' '.$results; if ($num_rows_members > 1){echo"s";} if ($num_rows_members > 15){echo ' '.$max_result;} echo '</span></h2>';

			if (!empty($city) AND empty($country))
			{
				$reponse = mysql_query("SELECT * FROM teen_quotes_account WHERE city LIKE '%".$city."%' AND hide_profile = '0' ORDER BY username ASC LIMIT 0,15");
			}
			elseif (empty($city) AND !empty($country))
			{
				$reponse = mysql_query("SELECT * FROM teen_quotes_account WHERE country LIKE '%".$country."%' AND hide_profile = '0' ORDER BY username ASC LIMIT 0,15");
			}
			elseif (empty($city) AND empty($country) AND !empty($value_search))
			{
				$reponse = $resultatsMembres;
			}

			while ($result = mysql_fetch_array($reponse))
			{
				$id_user = $result['id'];
				$avatar = $result['avatar'];
				$username_member = $result['username'];
				$about_me = $result['about_me'];
				$country = $result['country'];
				$city = $result['city'];

				$nb_quotes_approved = mysql_num_rows(mysql_query("SELECT id FROM teen_quotes_quotes WHERE auteur_id = '".$id_user."' AND approved = '1'"));
				$nb_quotes_submited = mysql_num_rows(mysql_query("SELECT id FROM teen_quotes_quotes WHERE auteur_id = '".$id_user."'"));
				$nb_favorite_quotes = mysql_num_rows(mysql_query("SELECT DISTINCT id_quote FROM teen_quotes_favorite WHERE id_user = '".$id_user."'"));
				$nb_comments = mysql_num_rows(mysql_query("SELECT id FROM teen_quotes_comments WHERE auteur_id = '".$id_user."'"));
				$nb_quotes_added_to_favorite = mysql_num_rows(mysql_query("SELECT F.id FROM teen_quotes_favorite F, teen_quotes_quotes Q WHERE F.id_quote = Q.id AND Q.auteur_id = '".$id_user."'"));


				echo '<div class="grey_post">';
					echo '<img src="http://'.$domain.'/images/avatar/'.$avatar.'" class="user_avatar_members" /><a href="user-'.$id_user.'"><h2>'.$username_member;
					
					if (!empty($country) OR !empty($city))
					{
						echo '<span class="right">';
					}

					if (!empty($city)) 
					{
						echo $city;
					}
					if (!empty($country))
					{
						if (!empty($city))
						{
							echo ' - ';
						}
						echo $country;
					}

					if (!empty($country) OR !empty($city))
					{
						echo '</span>';
					}
					echo '</h2></a>';

					if (!empty($about_me)) 
					{
						echo $about_me;
						echo '<div class="grey_line"></div>';
					}

					echo '
					<span class="bleu">'.$fav_quote.' :</span> '.$nb_favorite_quotes.'<br/>
					<span class="bleu">'.$number_comments.' :</span> '.$nb_comments.'<br/>
					<span class="bleu">'.$number_quotes.' :</span> '.$nb_quotes_approved.' '.$validees.' '.$nb_quotes_submited.' '.$soumises.'<br/>';

					if ($nb_quotes_approved > 0)
					{
						echo '
						<span class="bleu">'.$added_on_favorites.' :</span> '.$nb_quotes_added_to_favorite.'<br/>';
					}
				echo '</div>';

			} // Fin boucle membre
			echo '</div>';
		}
		// Fin du résultat des membres
	}
	// AFFICHAGE SI 0 RESULTAT
	else
	{ 
	echo '
	<div class="post">
		<h1><img src="http://'.$domain.'/images/icones/search_result.png" class="icone" />'.$no_result.'</h1>
		'.$no_result_fun.'
	</div>';
	}
}

include "footer.php";
?>