@echo off
set "target_folder=dlsongs"
mkdir "%target_folder%"

for /r %%f in (*.mp3) do (
    move "%%f" "%target_folder%"
)

echo All .mp3 files have been moved to the '%target_folder%' folder.
pause
