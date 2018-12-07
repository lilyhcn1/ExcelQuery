@echo off
for /f "tokens=1,2 delims==" %%i in (../phpStudy.ini) do (

  if "%%i"=="servlx"   (  rem echo %%j 
  if "%%j"=="4"  (
  echo "ÕýÔÚÍ£Ö¹IIS..."
  net stop w3svc 
  exit
  )
  )

 )

exit