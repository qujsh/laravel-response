<?php

namespace Qujsh\Response\Middlewares;

use Closure;
use Auth;
use Exception;

class ResponseCustomMiddleWare
{

    /**
     *
     * 此中间件默认不包含，404 Not Found，405 Method Not Allowed，因为这是在 Route端就进行返回设置了的
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $status = 'default')
    {
        $response = $next($request);

        $content = $response->getContent();

        $content = json_decode($content, 1);
        //        $status_code = $response->getStatusCode();
        $status_code = $content['status_code'];                 //为什么不用上面的内容，因为返回的状态码 和 content中的code会不一样

        if ($status_code == 200)       //表示成功
            return $response;

        //这儿应该有个报错，用来补充，未定义的数据抛错显示
        try{
            $status = config("permission.status_code")[$content['message']][$status];
        }catch (Exception $e){

            if (env('API_DEBUG', 'true')){
                return $response;

//                if (config("app.locale") == 'zh-CN'){
//                    $message = "未定义索引'".str_replace('Undefined index: ', '', $e->getMessage())."'，请先行添加";
//                }else{
//                    $message = $e->getMessage()."', please extend first.";
//                }
            }else{
                $message = '未定义错误，待处理中';
            }

            $response = [
                "code" => "2000000",
                "status_code" => "200",
                "message" => $message,
            ];

            return $response;
        }

        $extra = [
            "status_code" => $response->getStatusCode(),                //200
            "message" => "",
        ];

        $content['code'] = $status['code'];
        unset($content['status_code']);         //移除 status_code 用code代替

        switch ($status_code){
            case 422:
                $message = $status['message'];
                //表示 验证出错
                $errors = isset($content['errors'])?$content['errors']:[];
                foreach ($errors as $k=>$v){
                    $message = $v[0];
                    break;
                }

                $extra["message"] = $content['message'];
                $content['message'] = $message;             //顺序不能变，否则会被覆盖
                break;
            case 403:
            case 429:
            case 500:
                $extra["message"] = $content['message'];
                $content['message'] = $status['message'];
                break;
            default:    //这儿的参数性质是没有处理透的，所以，不能设置为默认，等之后如果设置大概完全，看处理是否可设置默认
//                $extra["status_code"] = $status_code;
//                $extra["message"] = $content['message'];
//                $content['message'] = $status['message'];
                break;
        }

        $response->setStatusCode(200);          //都调整为200，他们需要200

        //如果出现 extra 的status_code 和 外面的status_code不一样的情况，那就是 返回的http的status_code 和content status_code 不一致的情况
        $content['extra'] = $extra;
        $response->setContent($content);


        return $response;

    }
}
