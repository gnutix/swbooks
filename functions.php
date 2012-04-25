<?php
/**
 * Récupère le contenu d'un XML sous forme d'objet SimpleXML
 *
 * @param string $path Chemin d'accès du XML
 * @return SimpleXMLElement
 */
function getXML($path) {

	// On vérifie que le chemin du XML existe
	if (!file_exists($path)) return false;
	
	// On crée l'objet SimpleXML
	return simplexml_load_file($path);
}

/**
 * Récupère le contenu du XML et le transforme en tableau utilisable
 *
 * @param SimpleXMLElement $xml Objet du XML
 * @return array
 */
function displayBooksFromXML($xml) {

	// Divers
	$now = time();
	
	// Variables de check pour les rowspan
	$timeRowspan = 0;
	$authorRowspan = 0;
	$editorRowspanVO = 0;
	$editorRowspanVF = 0;
	
	// Vérifications
	$checktime = true;
	$checkAuthor = true;
	$checkEditorVO = true;
	$checkEditorVF = true;
	
	// Infos perso
	$checkInfosVO = true;
	$checkInfosVF = true;
	
	// Dates de publications
	$publishVO = 0;
	$publishVF = 0;
	
	// On boucle sur les ères
	foreach ($xml->books->era as $era) {
		echo '<tr><th colspan="9" class="'.$era['id'].'"><a href="#'.$era['id'].'" id="'.$era['id'].'" name="'.$era['id'].'">'.$era['name'].'</a></th></tr>';
		
		// Nombre de livres
		$nbBooks = count($era->book) - 1;
		
		// On prépare l'index du livre
		$index = 0;
		
		// On boucle sur les livres dans les ères
		foreach ($era->book as $book) {
		
			// Check des rowspan
			if ($checktime && $timeRowspan == 0) $timeRowspan = getNumberRowspan($era, $index, 'time');
			if ($checkAuthor && $authorRowspan == 0) $authorRowspan = getNumberRowspan($era, $index, 'author');
			if ($checkEditorVO && $editorRowspanVO == 0) $editorRowspanVO = getNumberRowspan($era, $index, 'editor', 'vo');
			if ($checkEditorVF && $editorRowspanVF == 0) $editorRowspanVF = getNumberRowspan($era, $index, 'editor', 'vf');
			
			// Début de ligne
			echo '<tr>';
			
			// Time - On met des acronymes pour BBY et ABY dans le time
			$book->time = str_replace('BBY', '<acronym title="Before Battle of Yavin" lang="en">BBY</acronym>', $book->time);
			$book->time = str_replace('ABY', '<acronym title="After Battle of Yavin" lang="en">ABY</acronym>', $book->time);
			
			// Time
			if ($checktime) echo '<td'.(($timeRowspan != 0) ? ' rowspan="'.$timeRowspan.'"' : null).'>'.$book->time.'</td>';
			
			// Title
			echo '<td class="'.$book['type'].'">';
			
			// Title - Series
			if (isset($book->series) && (string) $book->series != (string) $book->title) {
				echo $book->series;
				
				if (isset($book->series['number'])) echo ' '.$book->series['number'];				
				if ((string) $book->title != null) echo ': ';
			}
			
			echo $book->title.'</td>';
			
			// Author - On vérifie s'il y a plusieurs auteurs
			if (strstr($book->author, ',')) {
				
				// On split les auteurs et on enlève les espaces en trop
				$authors = explode(',', $book->author);
				
				// On prépare la liste à afficher
				$authorsList = '<ul class="authorsList">';
				
				// On boucle sur les auteurs
				foreach ($authors as $author) {
					$authorsList .= '<li>'.trim($author).'</li>';
				}
				
				// On fini la liste
				$authorsList .= '</ul>';
			}
			
			// Author
			if ($checkAuthor) echo '<td'.(($authorRowspan != 0) ? ' rowspan="'.$authorRowspan.'"' : null).'>'.(isset($authorsList) ? $authorsList : $book->author).'</td>';
			
			// Editor - On récupère le nom de l'éditeur VO
			$editorInfos['vo'] = ((string) $book->editor['vo'] == 'none') ? null : getEditorInfos($xml, $book->editor['vo'], 'en');
			$editorInfos['vf'] = ((string) $book->editor['vf'] == 'none') ? null : getEditorInfos($xml, $book->editor['vf'], 'fr');
			
			// Editor - VO
			if ($checkEditorVO) {
				echo '<td'.(($editorRowspanVO != 0) ? ' rowspan="'.$editorRowspanVO.'"' : null).' class="little">';
				
				if ((string) $book->editor['vo'] == null) {
					echo '<acronym title="The editor is unknown" lang="en">?</acronym>';
				} else if ((string) $book->editor['vo'] == null || (string) $book->editor['vo'] == 'none') {
					echo '<acronym title="There is no editor at the moment" lang="en">-</acronym>';
				} else {
					echo '<acronym title="'.$editorInfos['vo']['name'].'" lang="'.$editorInfos['vo']['lang'].'">'.strtoupper($book->editor['vo']).'</acronym>';
				}
				
				echo '</td>';
			}
			
			// Editor - VF
			if ($checkEditorVF) {
				echo '<td'.(($editorRowspanVF != 0) ? ' rowspan="'.$editorRowspanVF.'"' : null).' class="little">';
				
				if ((string) $book->editor['vf'] == 'unknown') {
					echo '<acronym title="The editor is unknown" lang="en">?</acronym>';
				} else if ((string) $book->editor['vf'] == null || (string) $book->editor['vf'] == 'none') {
					echo '<acronym title="There is no editor at the moment" lang="en">-</acronym>';
				} else {
					echo '<acronym title="'.$editorInfos['vf']['name'].'" lang="'.$editorInfos['vf']['lang'].'">'.strtoupper($book->editor['vf']).'</acronym>';
				}
				
				echo '</td>';
			}
			
			// En cas de date inconnue, on définit dans très très longtemps
			if ((string) $book->publish['vo'] == 'unknown') $publishVO = MAX_TIMESTAMP;
			if ((string) $book->publish['vf'] == 'unknown') $publishVF = MAX_TIMESTAMP;
			
			// On récupère la date de publication VO
			if ($publishVF == 0) {
				if ((string) $book->publish['vo'] != null && (string) $book->publish['vo'] != 'unknown') {
					$publishDateVO = explode('.', $book->publish['vo']);
					$publishVO = mktime(0, 0, 0, $publishDateVO[1], $publishDateVO[0], $publishDateVO[2]);
				}
			}
			
			// On récupère la date de publication VF
			if ($publishVF == 0) {
				if ((string) $book->publish['vf'] != null && (string) $book->publish['vf'] != 'unknown') {
					$publishDateVF = explode('.', $book->publish['vf']);
					$publishVF = mktime(0, 0, 0, $publishDateVF[1], $publishDateVF[0], $publishDateVF[2]);
				}
			}
			
			// VO
			if ((string) $book->editor['vo'] == 'none' || (string)$book->editor['vo'] == null || $publishVO > $now) {
				echo '<td class="little publish" colspan="2">';
				
				if (!empty($publishDateVO) || (string) $book->publish['vo'] == 'unknown' || (string) $book->publish['vo'] == null) {
					echo '<small>';
					
					if (!empty($publishDateVO)) {
						echo '<acronym title="This is the publication date" lang="en">'.displayDate($publishDateVO).'</acronym>';
					} else if ((string) $book->publish['vo'] == 'unknown') {
						echo '<acronym title="The publication date is unknown" lang="en">?</acronym>';
					} else {
						echo '<acronym title="We don\'t know if there will be a publication" lang="en">-</acronym>';
					}
					
					echo '</small></td>';
				}
				
				// On doit pas checker la 2ème case
				$checkInfosVO = false;
			} else {
				echo '<td class="little '.((string) $book->vo['copies'] ? 'green' : 'red').'">'.($book->vo['copies'] > 1 ? $book->vo['copies'] : null).'</td>';
			}
			
			// VO Readings
			if ($checkInfosVO) {
				echo '<td class="little '.((string) $book->vo['readings'] ? 'green' : 'red').'">'.($book->vo['readings'] > 1 ? $book->vo['readings'] : null).'</td>';
			} else {
				
				// On remet le truc à true
				$checkInfosVO = true;
			}
			
			// VF
			if ((string) $book->editor['vf'] == 'none' || (string)$book->editor['vf'] == null || $publishVF > $now) {
				echo '<td class="little publish" colspan="2">';
				
				if (!empty($publishDateVF) || (string) $book->publish['vf'] == 'unknown' || (string) $book->publish['vf'] == null) {
					echo '<small>';
					
					if (!empty($publishDateVF)) {
						echo '<acronym title="This is the publication date" lang="en">'.displayDate($publishDateVF).'</acronym>';
					} else if ((string) $book->publish['vf'] == 'unknown') {
						echo '<acronym title="The publication date is unknown" lang="en">?</acronym>';
					} else {
						echo '<acronym title="We don\'t know if there will be a publication" lang="en">-</acronym>';
					}
					
					echo '</small></td>';
				}
				
				// On doit pas checker la 2ème case
				$checkInfosVF = false;
			} else {
				echo '<td class="little '.((string) $book->vf['copies'] ? 'green' : 'red').'">'.($book->vf['copies'] > 1 ? $book->vf['copies'] : null).'</td>';
			}
				
			// VF Readings
			if ($checkInfosVF) {
				echo '<td class="little '.((string) $book->vf['readings'] ? 'green' : 'red').'">'.($book->vf['readings'] > 1 ? $book->vf['readings'] : null).'</td>';
			} else {
				
				// On remet le truc à true
				$checkInfosVF = true;
			}
			
			// Fin de ligne
			echo '</tr>'.PHP_EOL;
			
			// Si c'est le dernier index, on met un séparateur
			if ($index == $nbBooks) echo '<tr><td colspan="9" class="separator">Séparateur</td></tr>';
			
			// Décrémentation et changement des vérifications
			if ($timeRowspan > 1) {
				$timeRowspan--;
				$checktime = false;
			} else {
				$timeRowspan = 0;
				$checktime = true;
			}

			if ($authorRowspan > 1) {
				$authorRowspan--;
				$checkAuthor = false;
			} else {
				$authorRowspan = 0;
				$checkAuthor = true;
			}

			if ($editorRowspanVO > 1) {
				$editorRowspanVO--;
				$checkEditorVO = false;
			} else {
				$editorRowspanVO = 0;
				$checkEditorVO = true;
			}

			if ($editorRowspanVF > 1) {
				$editorRowspanVF--;
				$checkEditorVF = false;
			} else {
				$editorRowspanVF = 0;
				$checkEditorVF = true;
			}
			
			// Remise à zéro de variables de tests
			if (isset($authorsList)) unset($authorsList);
			if ($publishVO > 0) $publishDateVO = array(); $publishVO = 0;
			if ($publishVF > 0) $publishDateVF = array(); $publishVF = 0;
		
			// On incrémente l'index du livre
			$index++;
		}
	}
}

/**
 * Fonction de récupération du nombre de rowspans à effectuer
 *
 * @param array $books Tableau contenant une série de livres
 * @param int $index Index de l'élément de base
 * @param string $node Noeud à comparer
 * @param string $attribute Attribut à comparer (optionnel)
 * @return int Nombre de rowspan à effectuer
 */
function getNumberRowspan($era, $index, $node, $attribute = null) {

	//echo 'test '.$era[0]->book[0]->editor['vo'].'<br />';
	
	// On prépare l'itérateur pour les rowspan
	$rowspan = 0;
	$iterator = 0;
	
	// On boucle sur le tableau
	foreach ($era->book as $book) {
	
		// On repart à partir de l'enregistrement duquel est appelé la fonction
		if ($iterator >= $index) {
		
			// On vérifie en fonction du type
			if ($attribute == null) {
		
				// On vérifie si l'élément est le même que le premier sauvé (et on converti en string à cause de simplexml)
				if ((string) $era->book[$index]->$node == (string) $book->$node) {
					$rowspan++;
				} else {
					break;
				}
				
			} else {
		
				// On vérifie si l'élément est le même que le premier sauvé (et on converti en string à cause de simplexml)
				if ((string) $era->book[$index]->{$node}[$attribute] == (string) $book->{$node}[$attribute]) {
					$rowspan++;
				} else {
					break;
				}
			}
		}
		
		// On incrémente l'itérateur
		$iterator++;
	}
	
	// On retourne les rowspan
	return $rowspan;
}

/**
 * Récupère le nom de l'éditeur dans la langue demandée
 *
 * @param string $xml
 * @param string $editor
 * @param string $lang
 * @return array
 */
function getEditorInfos($xml, $editor, $lang) {
	
	// On vérifie que c'est bien une chaine
	if (!(string) $editor) return false;
	
	// On récupère le nom de l'éditeur
	$editorInfos = $xml->xpath('//informations/editors/editor[@code=\''.$editor.'\'][@lang=\''.$lang.'\']');
	
	// On retourne
	return $editorInfos[0];
}

/**
 * Affiche correctement la date à partir d'un tableau
 *
 * @param array $params
 * @return string
 */
function displayDate($params) {
	$date = ($params[0] != '00') ? $params[0].'.' : null;
	$date .= ($params[1] != '00') ? $params[1].'.' : null;
	$date .= $params[2];
	
	return $date;
}
?>