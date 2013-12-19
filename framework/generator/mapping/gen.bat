@echo off
cls

set CHOCALA_PROJECT_BASE=%~dp0..\..

set CHOCALA_PROJECT_FW=%CHOCALA_PROJECT_BASE%

set CHOCALA_PROJECT_APP=%CHOCALA_PROJECT_BASE%\..\application

set CHOCALA_GEN_HOME=%CHOCALA_PROJECT_FW%\orm\propel\generator\bin

echo CHOCALA FRAMEWORK 
echo *********************************************
echo *                                           * 
echo * Initializing Chocala generation...        * 
echo *                                           * 
echo *********************************************
echo Setting PROPEL_GEN_HOME: %CHOCALA_GEN_HOME%

if "%OS%"=="Windows_NT" @setlocal

set PROPEL_GEN_HOME=%CHOCALA_GEN_HOME%\..

if "%PHING_COMMAND%" == "" set PHING_COMMAND=phing.bat

cd %~dp0
rem echo %CD%
rem cd %~dp0
echo .............................................

set nbArgs=0
for %%x in (%*) do Set /A nbArgs+=1
if %nbArgs% leq 1 (
  %PHING_COMMAND% -f "%PROPEL_GEN_HOME%\build.xml" -Dusing.propel-gen=true -Dproject.dir="%CD%" %*
) else (
  %PHING_COMMAND% -f "%PROPEL_GEN_HOME%\build.xml" -Dusing.propel-gen=true -Dproject.dir=%*
)

set nbArgs=0
  
if "%OS%"=="Windows_NT" @endlocal