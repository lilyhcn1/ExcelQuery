net stop w3svc
del %windir%\system32\php5ts.dll  /q
del %windir%\system32\libmysql.dll  /q
del %windir%\system32\libmcrypt.dll  /q
del %windir%\system32\libeay32.dll  /q
del %windir%\system32\ssleay32.dll  /q
del %windir%\php.ini  /q
net start w3svc
