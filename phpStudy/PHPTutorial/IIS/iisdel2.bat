
%windir%\system32\inetsrv\appcmd.exe set config /section:handlers /-[name='php_FastCGI']
%windir%\system32\inetsrv\appcmd.exe set config /section:handlers /-[name='php_FastCGI']
%windir%\system32\inetsrv\appcmd delete site "php-2017"

