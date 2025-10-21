@echo off
echo 🚀 Автодеплой запущен...

REM Добавляем изменения и пушим
git add .
git commit -m "Auto commit before deplo1y"
git push origin master

REM Сборка проекта
echo ⚙️ Сборка проекта...
npm run build

REM Добавляем сборку в git
git add -f public/build/
git commit -m "Build assets for production"
git push origin master

echo ✅ Локальный деплой завершен
pause
