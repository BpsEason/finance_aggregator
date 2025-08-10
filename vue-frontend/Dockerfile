# ./vue-frontend/Dockerfile
FROM node:18-alpine

WORKDIR /app

COPY package.json package-lock.json ./

# 安裝相依性 (此步驟將在容器內部執行，非本機)
# RUN npm install

COPY .travis.yml .
COPY . .

EXPOSE 5173

# 啟動 Vite 開發伺服器
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
