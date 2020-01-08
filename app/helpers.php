<?php

// 改写路由名称
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

// 判断路由激活状态
function category_nav_active($category_id)
{
    return active_class(if_route('categories.show') && if_route_param('category', $category_id));
}

// 获取摘录
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}
