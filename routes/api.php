<?php

use Illuminate\Http\Request;

Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {
        Route::middleware('throttle:' . config('api.rate_limits.sign'), 'cors')
            ->group(function () {
                // 短信验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')
                ->name('verificationCodes.store');
                // 用户注册
                Route::post('users', 'UsersController@store')
                    ->name('users.store');
                // 登录
                Route::post('authorizations', 'AuthorizationsController@store')
                ->name('api.authorizations.store');
                // 刷新token
                Route::put('authorizations/current', 'AuthorizationsController@update')
                    ->name('api.authorizations.update');
                // 删除token
                Route::delete('authorizations/current', 'AuthorizationsController@destroy')
                    ->name('api.authorizations.destroy');
        });

        Route::middleware('throttle:' . config('api.rate_limits.access'), 'cors')
            ->group(function () {
                // 游客可以访问的接口
                // 2.获取博文(根据id)
                Route::get('articles', 'ArticlesController@show')
                ->name('articles.show');

                // 获取我的信息 (头像地址、用户名、描述信息)
                Route::get('avatar', 'UsersController@showme')
                ->name('avatar.showme');

                // 5.获取博文集合
                Route::get('articles/resources', 'ArticlesController@collection')
                ->name('articles.collection');

                // 登录后可以访问的接口 (需要 token 验证的接口)
                Route::middleware('auth:api')->group(function() {
                    // 当前登录用户信息
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');
                    // 编辑登录用户信息
                    Route::patch('user', 'UsersController@update')
                        ->name('user.update');

                    // 博客相关
                    // 1.发表博文
                    Route::post('articles', 'ArticlesController@store')
                    ->name('articles.store');

                    // 3.删除博文
                    Route::delete('articles', 'ArticlesController@destroy')
                        ->name('articles.destroy');

                    // 4.更新博文
                    Route::patch('articles', 'ArticlesController@update')
                        ->name('articles.update');

                    // 6.上传图片
                    Route::post('images', 'ImagesController@store')
                    ->name('images.store');
                });
            });
    });
