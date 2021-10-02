## Requirement
1. php 7.2|7.3
2. xhprof扩展
3. tideways扩展
4. mongodb扩展

## 配置
*添加事件监听*
```php
# app/event.php
return [
    'listen'    => [
        'HttpRun'  => [
            "thinkTideways\\tideways\\listener\\TidewaysEnable"
        ],
        'HttpEnd'  => [
            "thinkTideways\\tideways\\listener\\TidewaysDisable"
        ],
    ],
];
```

*添加APP变量*
```
[TIDEWAYS]
enable=true
mongodb_host=mongodb://yourhost:27017
mongodb_db=xhprof
mongodb_username=yourusername
mongodb_password=yourpassword
```