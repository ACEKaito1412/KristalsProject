<?php
$page_container = './pages/main.php';
if (isset($_GET['pages'])) {
    $pages = $_GET['pages'];
    echo ' ' . $pages . ' ';

    switch ($pages) {
        case 'home':
            $page_container = './pages/home.php';
            break;
        case 'category':
            $page_container = './pages/food.php';
            break;
        case 'add':
            $page_container = './pages/add.php';
            break;
        case 'recipe':
            $page_container = './pages/add-recipe.php';
            break;
    }
    echo $page_container;
}
