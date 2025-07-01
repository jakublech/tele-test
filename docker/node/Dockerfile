FROM node:22

COPY frontend/package.json /var/www/frontend/package.json

WORKDIR /var/www/frontend

RUN npm install

COPY . .

EXPOSE 3000