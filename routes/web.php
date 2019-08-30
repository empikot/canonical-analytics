<?php

Route::get('/', 'Controller@index');
Route::get('/health', 'Controller@index');
Route::get('/canonical-report.csv', 'Controller@canonicalReport');
