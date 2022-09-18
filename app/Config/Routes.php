<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('App');
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
$routes->get('/', 'App::index');
$routes->get('/recover', 'App::recover');
$routes->get('/audits/get', 'AuditController::getAudits');

//USERS MODULE
$routes->group('users', static function ($routes) {
    $routes->post('signin', 'UserController::signin');
    $routes->post('create', 'UserController::createUser');
    $routes->get('get', 'UserController::getUsers');
    $routes->get('getById/(:num)', 'UserController::getUserById/$1');
    $routes->post('update', 'UserController::updateUser');
    $routes->post('delete', 'UserController::deleteUser');
});

//CATEGORIES MODULE
$routes->group('categories', static function ($routes) {
    $routes->post('create', 'CategoryController::createCategory');
    $routes->get('get', 'CategoryController::getCategories');
    $routes->get('getById/(:num)', 'CategoryController::getCategoryById/$1');
    $routes->post('update', 'CategoryController::updateCategory');
    $routes->post('delete', 'CategoryController::deleteCategory');
});

//BRANDS MODULE
$routes->group('brands', static function ($routes) {
    $routes->post('create', 'BrandController::createBrand');
    $routes->get('get', 'BrandController::getBrands');
    $routes->get('getById/(:num)', 'BrandController::getBrandById/$1');
    $routes->post('update', 'BrandController::updateBrand');
    $routes->post('delete', 'BrandController::deleteBrand');
});

//COINS MODULE
$routes->group('coins', static function ($routes) {
    $routes->post('create', 'CoinController::createCoin');
    $routes->get('get', 'CoinController::getCoins');
    $routes->get('getById/(:num)', 'CoinController::getCoinById/$1');
    $routes->post('update', 'CoinController::updateCoin');
    $routes->post('delete', 'CoinController::deleteCoin');
});

//TAXES MODULE
$routes->group('taxes', static function ($routes) {
    $routes->post('create', 'TaxController::createTax');
    $routes->get('get', 'TaxController::getTaxes');
    $routes->get('getById/(:num)', 'TaxController::getTaxById/$1');
    $routes->post('update', 'TaxController::updateTax');
    $routes->post('delete', 'TaxController::deleteTax');
});

//PRODUCTS MODULE
$routes->group('products', static function ($routes) {
    $routes->post('create', 'ProductController::createProduct');
    $routes->get('get', 'ProductController::getProducts');
    $routes->get('getById/(:num)', 'ProductController::getProductById/$1');
    $routes->post('update', 'ProductController::updateProduct');
    $routes->post('delete', 'ProductController::deleteProduct');
});

//SETTINGS MODULE
$routes->group('settings', static function ($routes) {
    $routes->post('setCoins', 'SettingController::setCoins');
});



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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
