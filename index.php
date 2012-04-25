<?php
/**
 * Gestion des livres
 *
 * @author dvi
 * @version 1.0
 */
 
// Maximum possible timestamp
define('MAX_TIMESTAMP', 2147483647);

// Configuration de la date pour PHP5
date_default_timezone_set('Europe/Zurich');

// On inclu les fonctions
include('functions.php');

// On récupère le XML
$xml = getXML('books.xml');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Star Wars Books</title>
	
	<link href="template/css/standard.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<div id="global">
		<div id="content">
			<h1>Star Wars Books</h1>
			
			<p>This is the list of Star Wars books and comics with the main information about each of them. Featuring my possessions and my readings.</p>
			
			<h2>Books' editors</h2>
			
			<dl class="editors">
				<?php				
				foreach ($xml->informations->editors->editor as $editor) {
					echo '<dt><acronym title="'.$editor['name'].'" lang="'.$editor['lang'].'">'.strtoupper($editor['code']).'</acronym></dt><dd>'.$editor['name'].'</dd>';
				}
				?>
			</dl>
			
			<div class="spacer"></div>
			
			<h2>Books' types</h2>
			
			<ul class="legend">
				<?php
				foreach ($xml->informations->types->type as $type) {
					echo '<li class="'.$type['code'].'">'.$type['name'].'</li>';
				}
				?>
			</ul>
			
			<div class="spacer"></div>
			
			<h2>Shortcuts to the eras</h2>
			
			<ul class="shortcuts">
				<?php
				foreach ($xml->books->era as $era) {
					echo '<li class="'.$era['id'].'"><a href="#'.$era['id'].'">'.$era['name'].'</a></li>';
				}
				?>
			</ul>
			
			<h2>Books' list</h2>
			
			<table class="booklist">
				<thead>
					<tr>
						<th class="timeline">Timeline</th>
						<th class="title">Title</th>
						<th class="author">Author(s)</th>
						<th class="voe"><abbr title="Original Version editor" lang="fr">VO-E</abbr></th>
						<th class="vfe"><abbr title="French Version editor" lang="fr">VF-E</abbr></th>
						<th class="vo"><abbr title="Do I have the Original Version ? How many copies ?" lang="fr">VO</abbr></th>
						<th class="vor"><abbr title="Did I read the Original Version ? How many times ?" lang="fr">VO-R</abbr></th>
						<th class="vf"><abbr title="Do I have the French Version ? How many copies ?" lang="fr">VF</abbr></th>
						<th class="vfr"><abbr title="Did I read the French Version ? How many times ?" lang="fr">VF-R</abbr></th>
					</tr>
				</thead>
				<tbody>
					<?php displayBooksFromXML($xml); ?>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>