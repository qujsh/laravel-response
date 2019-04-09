<?php

//status_code 状态码的定义，最后的返回结果，都需要对status_code 进行重新定义，原 status 和 message组合得到的返回值能体现的内容真是有点少
//原来状态，如：status: 403
//{
//    "status_code": 403,
//    "message": "User does not have the right permissions."
//}

//现在期望的状态，如：status: 200
//{
//    "code": 40300xx,
//    "message": "custom message",
//    "extra": {
//        "status_code": 403,               //原 status_code 值
//        "message": "User does not have the right permissions.",       //原 message 内容
//        "other": "xxxx"
//    }
//}

//status_code 的值为 http状态值+0000 的值

return [
    //还不行，没有足够的范本

    /********************************* 403 ********************************************/
    "User does not have the right permissions." => [
        "default" => [      //默认的状态码，message值显示；一个message 对应一个default 内容
            "code" => "4030002",
            "message" => "没有足够的权限",
        ],
    ],

    "User is not logged in." => [
        "default" => [
            "code" => "4030001",
            "message" => "用户没有登录",
        ],
    ],

    "logined in other device" => [
        "default" => [
            "code" => "4030003",
            "message" => "已在其他客户端上线",
        ],
    ],

    /********************************* 422 ********************************************/

    "422 Unprocessable Entity" => [     //表示参数传递有问题
        "default" => [
            "code" => "4220000",
            "message" => "参数传递不正确",
        ],
    ],

    "The given data was invalid." => [     //表示参数传递有问题
        "default" => [
            "code" => "4220001",
            "message" => "参数传递不正确",
        ],
    ],

    /********************************* 429 ********************************************/

    "Too Many Attempts." => [     //表示参数传递有问题
        "default" => [
            "code" => "4290000",
            "message" => "太多次访问",
        ],
    ],

    /********************************* 500 ********************************************/

    "The token has been blacklisted" => [
        "default" => [
            "code" => "5000001",
            "message" => "用户已过期",
        ],
    ],


    "Token has expired and can no longer be refreshed" => [
        "default" => [
            "code" => "5000002",
            "message" => "用户已过期",
        ],
    ],

];

