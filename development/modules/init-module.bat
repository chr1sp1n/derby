
@ECHO OFF
SET ERROR=0
IF [%1] == [] (
  ECHO [ERRO] Theme name parameter needed.
  EXIT 1
) ELSE (
  IF [%1] == ".template" (
    ECHO [ERRO] Invalid theme name.
  ) ELSE (
    IF EXIST %1 (

      ECHO [ERRO] Theme already exists.
      EXIT 1

    ) ELSE (

      ECHO [INFO] Cloning template theme...
      Xcopy /E /I ".template" "%1"
      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )
      powershell -Command "(gc %1\drussets.config.json) -replace '{{theme-name}}', '%1' | Out-File -encoding ASCII %1\drussets.config.json"
      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )

      CD "%1\src\"

      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )
      REN "info.yml" "%1.info.yml"
      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )
      REN "libraries.yml" "%1.libraries.yml"
      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )
      REN ".theme" "%1.theme"
      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )

      powershell -Command "(gc %1.theme) -replace 'theme_', '%1_' | Out-File -encoding ASCII %1.theme"
      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )
      powershell -Command "(gc %1.info.yml) -replace '{{theme-name}}', '%1' | Out-File -encoding ASCII %1.info.yml"
      IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )


      IF %ERROR% EQU 0  (
        CD "..\"
        ECHO [INFO] Performing npm install.
        npm install
        IF %ERRORLEVEL% NEQ 0 ( SET ERROR=%ERRORLEVEL% )
      )

      IF %ERROR% EQU 0  (
        ECHO [.OK.] All operations succesfully completed.
        exit 0
      ) ELSE (
        ECHO [ERRO] Operations execution fail.
        EXIT 1
      )

    )
  )
)

