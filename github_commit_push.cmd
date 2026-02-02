@echo off
echo Starting GitHub Backup...
cd /d "%~dp0"

REM Create timestamped MySQL backup
echo Creating MySQL backup...
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I
set timestamp=%datetime:~0,8%_%datetime:~8,6%
set "backup_file=marsey1_db_backup_%timestamp%.sql"
echo Backup file: %backup_file%
d:\xampp\mysql\bin\mysqldump.exe -u root marsey1 > "%backup_file%"
echo MySQL backup created: %backup_file%

REM Git operations
echo Checking git status...
git status

REM Add all changes including deletions
echo Adding changes...
git add -A

REM Commit changes
echo Committing changes...
git commit -m "Auto backup %DATE% %TIME%"
if errorlevel 1 (
    echo No changes to commit or commit failed
    pause
    exit /b 1
)

REM Pull remote changes and merge
echo Pulling remote changes...
git pull origin main --no-rebase
if errorlevel 1 (
    echo Pull failed - there might be conflicts
    echo Please resolve conflicts manually
    pause
    exit /b 1
)

REM Push to remote
echo Pushing to GitHub...
git push origin main
if errorlevel 1 (
    echo Push failed
    pause
    exit /b 1
)

echo.
echo ✅ Backup completed successfully!
exit
