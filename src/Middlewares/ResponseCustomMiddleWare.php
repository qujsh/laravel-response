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

        $status_code = $response->getStatusCode();
        $content = $response->getContent();

        $content = json_decode($content, 1);

        if ($status_code == 200)       //表示成功
            return $response;

        //这儿应该有个报错，用来补充，未定义的数据抛错显示
        try{
            $status = config("response.message")[$content['message']][$status];
        }catch (Exception $e){
            if (config("app.locale") == 'zh-CN'){
                $message = "未定义索引'".str_replace('Undefined index: ', '', $e->getMessage())."'，请先行添加";
            }else{
                $message = $e->getMessage()."', please extend first.";
            }

            $response = [
                "status_code" => "2000000",
                "message" => $message,
            ];

            return $response;
        }

        $extra = [
            "status_code" => 200,
            "message" => "",
        ];

        $content['status_code'] = $status['status_code'];

        switch ($status_code){
            case 422:
                $message = $status['message'];
                //表示 验证出错
                $errors = isset($content['errors'])?$content['errors']:[];
                foreach ($errors as $k=>$v){
                    $message = $v[0];
                    break;
                }

                $extra["status_code"] = $status_code;
                $extra["message"] = $content['message'];
                $content['message'] = $message;             //顺序不能变，否则会被覆盖
                break;
            case 403:
            case 429:
            case 500:
                $extra["status_code"] = $status_code;
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

        $content['extra'] = $extra;
        $response->setContent($content);


        return $response;

    }
}
