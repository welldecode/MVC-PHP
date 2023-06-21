<?php
 

$router->get('/')->action('Home#index')->name('home'); 
 
$router->get('/admin')->action('admin#painel')->name('admin');