<?php

// +----------------------------------------------------------------------
// | Library for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2020 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://library.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/zoujingli/ThinkLibrary
// | github 仓库地址 ：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

// 注册系统常用指令
if (class_exists('think\Console')) {
    think\Console::addDefaultCommands([
        // 注册清理无效会话
        '\\library\\command\\Sess',
        // 注册系统任务指令
        '\\library\\queue\\WorkQueue',
        '\\library\\queue\\StopQueue',
        '\\library\\queue\\StateQueue',
        '\\library\\queue\\StartQueue',
        '\\library\\queue\\QueryQueue',
        '\\library\\queue\\ListenQueue',
        // 注册系统更新指令
        '\\library\\command\\sync\\Admin',
        '\\library\\command\\sync\\Plugs',
        '\\library\\command\\sync\\Config',
        '\\library\\command\\sync\\Wechat',
        '\\library\\command\\sync\\Service',
    ]);
}

if (PHP_SAPI !== 'cli') {
    // 注册跨域中间键
    think\facade\Middleware::add(function (\think\Request $request, \Closure $next, $header = []) {
        if (($origin = $request->header('origin', '*')) !== '*') {
            $header['Access-Control-Allow-Origin'] = $origin;
            $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE';
            $header['Access-Control-Allow-Headers'] = 'Authorization,Content-Type,If-Match,If-Modified-Since,If-None-Match,If-Unmodified-Since,X-Requested-With';
            $header['Access-Control-Expose-Headers'] = 'User-Token-Csrf';
        }
        if ($request->isOptions()) {
            return response()->code(204)->header($header);
        } else {
            return $next($request)->header($header);
        }
    });
}

// 动态加载模块配置
if (function_exists('think\__include_file')) {
    // 加载对应的语言包
    think\facade\Lang::load(__DIR__ . '/lang/zh-cn.php', 'zh-cn');
    think\facade\Lang::load(__DIR__ . '/lang/en-us.php', 'en-us');

    $root = rtrim(str_replace('\\', '/', think\facade\Env::get('app_path')), '/');

    foreach (glob("{$root}/*/sys.php") as $file) {
        \think\__include_file($file);
    }
}
