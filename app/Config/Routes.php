<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Konfigurasi Routing Aplikasi KlinPro
 * 
 * Mendefinisikan semua URL endpoint dan menghubungkannya
 * ke Controller dan Method yang sesuai.
 * 
 * @var RouteCollection $routes
 */

// Halaman Login (default landing page)
$routes->get('/', 'Auth::index');

// Proses Autentikasi
$routes->post('/auth/process', 'Auth::process');
$routes->get('/auth/logout', 'Auth::logout');

// Dashboard (dilindungi oleh session check di controller)
$routes->get('/dashboard', 'Dashboard::index');
