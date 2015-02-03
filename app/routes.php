<?php
        namespace App;

        use Clocanth\Route;

        Route::get('/', 'IndexController@index');

        Route::group('users', function() {
            Route::get('all', 'UserController@all');
        });
