<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Admin');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Admin::index');
$routes->get('/admin', 'Admin::index');
$routes->group('admin', function ($routes) {
    $routes->add('login', 'Admin::login');
    $routes->get('logout', 'Admin::logout');
});
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->group('dashboard', function ($routes) {
    $routes->get('list_book', 'Book::getCategoryFromDatabase', ['filter' => 'auth']);
    $routes->get('add_book', 'Book::addBook', ['filter' => 'auth']);
    $routes->add('add_book/new', 'Book::newBook', ['filter' => 'auth']);
    $routes->get('edit_book/(:any)', 'Book::editBook/$1');
    $routes->get('delete_book/(:segment)/confirm', 'Book::deleteBook/$1');
    $routes->add('edit_book/(:segment)/save', 'Book::confirmUpdate/$1');
    $routes->get('list_member', 'Dashboard::getAllMembersFromDatabase', ['filter' => 'auth']);
    $routes->add('add_category', 'Book::ajaxAddNewCategory', ['filter' => 'auth']);
    $routes->add('edit_category/(:any)', 'Book::ajaxEditCategory/$1', ['filter' => 'auth']);
    $routes->get('list_book_by_category/(:any)', 'Book::ajaxGetAllBookFromDatabase/$1', ['filter' => 'auth']);
});
$routes->get('/member', 'Member::index');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
