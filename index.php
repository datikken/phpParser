<?php 
//db
$user = 'root';
$password = 'root';
$db = 'articles';
$host = 'localhost';
$port = 8889;
//db connect
$dsn = 'mysql:host='.$host.';dbname='.$db;
$pdo = new PDO($dsn, $user, $password);

$query = $pdo->query('SELECT * FROM `articles`');
while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo $row['url'] . '<br/>';
};

$url = 'google.com';
$h1 = 'Some h1';
$content = 'Some content'; 

$sql = 'INSERT INTO articles(url, h1, content) VALUES(:url, :h1, :content)';
$query = $pdo->prepare($sql);
$query->execute(['url' => $url, 'h1' => $h1, 'content' => $content]);

//lib for parse
require_once "simple_html_dom.php";

//parse url
$url = "http://ananaska.com/vse-novosti/";

function getArticleData($url) {
    $article = file_get_html($url);

    $h1 = $article->find('h1', 0 )->innertext;
    $content = $article->find('article', 0 )->innertext;

    // $data = array('h1' => $h1, 'content' => $content);
    $data = compact('h1', 'content');
    return $data;
}

function getArticlesLinksFromCatalog($url) {

    echo $url.PHP_EOL.PHP_EOL;
    //get page
    $html = file_get_html($url);
    foreach ($html->find( 'a.read-more-link') as $link_to_article) {
        echo $link_to_article->href . PHP_EOL;
        print_r(getArticleData($link_to_article->href));
    }
    //recursion to next page
    if($next_link = $html->find('a.next', 0)) {
        getArticlesLinksFromCatalog($next_link->href);
    }
}

getArticlesLinksFromCatalog($url)
?>