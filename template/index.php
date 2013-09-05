<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Star Wars Books</title>

    <link href="template/css/standard.css" rel="stylesheet" type="text/css" media="all">
</head>
<body>
<div id="global">
    <div id="content">
        <h1>Star Wars Books</h1>
        <p>
            This is the list of Star Wars books and comics with the main information about each of them.
            Featuring my possessions and my readings.
        </p>

        <h2>Books' editors</h2>
        <dl class="editors">
            <?php
            foreach ($library->getEditors() as $editor) {
                echo '<dt>'.
                        '<acronym title="'.$editor->getName().'" lang="'.$editor->getPreferredLanguage().'">'.
                            strtoupper($editor->getId()).
                        '</acronym>'.
                    '</dt>
                    <dd>'.$editor->getName().'</dd>';
            }
            ?>
        </dl>

        <div class="spacer"></div>

        <h2>Books' types</h2>
        <ul class="legend">
            <?php
            foreach ($library->getCategories() as $category) {
                echo '<li class="'.$category->getId().'">'.$category->getName().'</li>';
            }
            ?>
        </ul>

        <div class="spacer"></div>

        <h2>Shortcuts to the eras</h2>
        <ul class="shortcuts">
            <?php
            foreach ($library->getEras() as $era) {
                echo '<li class="'.$era->getId().'"><a href="#'.$era->getId().'">'.$era->getName().'</a></li>';
            }
            ?>
        </ul>

        <h2>Books' list</h2>
        <table class="books-list">
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
                <?php displayBooksFromXML($xmlFileLoader->getData()); ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
