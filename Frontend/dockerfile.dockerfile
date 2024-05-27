
FROM node:14-alpine


WORKDIR /app


COPY . .


RUN npm install


EXPOSE 19000
EXPOSE 19001
EXPOSE 19002


CMD npm start
