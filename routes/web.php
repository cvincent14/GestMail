<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', 'MailController@listerMails');
Route::get('/filtreSoci', 'MailController@filtreSoci');
Route::get('/filtreNonSoci', 'MailController@filtreNonSoci');
Route::get('/noFiltre', 'MailController@noFiltre');
Route::get('/filtreSpam', 'MailController@filtreSpam');
Route::post('/ajoutContact', 'MailController@ajoutContact');
Route::post('/supprimer', 'MailController@supprimerMail');