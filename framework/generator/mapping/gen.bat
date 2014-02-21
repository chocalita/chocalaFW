@echo off

set CHOCALA_PROJECT_BASE=%~dp0..\..
set CHOCALA_PROJECT_FW=%CHOCALA_PROJECT_BASE%
set CHOCALA_PROJECT_APP=%CHOCALA_PROJECT_BASE%\..\application
set CHOCALA_GEN_HOME=%CHOCALA_PROJECT_FW%\orm\propel\generator\bin

if "%PHP_COMMAND%" == "" set PHP_COMMAND=%1
if "%PHING_HOME%" == "" set PHING_HOME=%CHOCALA_PROJECT_FW%\lib\phing
if "%PHP_CLASSPATH%" == "" set PHP_CLASSPATH=%PHING_HOME%\classes
set PATH=%PATH%;%PHING_HOME%\bin

set PROPEL_GEN_HOME=%CHOCALA_GEN_HOME%\..

echo     *********************************************
echo     *     C H O C A L A   F R A M E W O R K     *
echo     *********************************************
echo     *                                           * 
echo     * Initializing Chocala generation...        * 
echo     *                                           * 
echo     *********************************************
echo     + Setting PROPEL_GEN_HOME: %PROPEL_GEN_HOME%

if "%OS%"=="Windows_NT" @setlocal
if "%PHING_COMMAND%" == "" set PHING_COMMAND=phing.bat

cd %~dp0
rem echo %CD%
rem cd %~dp0

shift
set "args="
:parse
if "%~1" neq "" (
  set args=%args% %1
  shift
  goto :parse
)
if defined args set args=%args:~1%

set nbArgs=0
for %%x in (%args%) do Set /A nbArgs+=1
if %nbArgs% leq 1 (
  %PHING_COMMAND% -f "%PROPEL_GEN_HOME%\build.xml" -Dusing.propel-gen=true -Dproject.dir="%CD%" %args%
) else (
  %PHING_COMMAND% -f "%PROPEL_GEN_HOME%\build.xml" -Dusing.propel-gen=true -Dproject.dir=%args%
)
set nbArgs=0
  
if "%OS%"=="Windows_NT" @endlocal