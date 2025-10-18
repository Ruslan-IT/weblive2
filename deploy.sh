#!/bin/bash
set -e

# Коммиты локальных изменений и пуш в GitHub
git add .
git commit -m "Auto commit before deploy"
git push origin master

# Сборка проекта (если нужно)
npm run build

# Добавляем сборку в git
git add -f public/build/
git commit -m "Build assets for production"
git push origin master

echo "✅ Локальный деплой готов, код отправлен на GitHub"

