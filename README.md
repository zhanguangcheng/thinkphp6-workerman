# thinkphp6-workerman

This project uses the [ThinkPHP6.1 framework](https://www.thinkphp.cn/) and [Linkerman](https://github.com/zhanguangcheng/linkerman) (based on [Workerman](https://www.workerman.net)) to build a project template.

The purpose is to run the ThinkPHP6.1 framework in Workerman to implement the resident memory to improve the performance.

## Requirements

- PHP >= 8.0

## Installation

```bash
git clone https://github.com/zhanguangcheng/thinkphp6-workerman.git
cd thinkphp6-workerman
composer install --optimize-autoloader --classmap-authoritative
```

## Start the service

Add to php.ini file
```ini
disable_functions=set_time_limit,header,header_remove,headers_sent,headers_list,http_response_code,setcookie,setrawcookie,session_start,session_id,session_name,session_save_path,session_status,session_write_close,session_regenerate_id,session_unset,session_destroy,is_uploaded_file,move_uploaded_file
```

```bash
php server.php start
```

## Security Vulnerabilities

If you discover a security vulnerability within thinkphp6-workerman, Please submit an [issue](https://github.com/zhanguangcheng/thinkphp6-workerman/issues) or send an e-mail to zhanguangcheng at 14712905@qq.com. All security vulnerabilities will be
promptly addressed.

## References

* <https://github.com/joanhey/AdapterMan/blob/master/src/frameworks/think.php>

