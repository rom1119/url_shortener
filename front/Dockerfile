# Base image
FROM node:14

# Set the working directory inside the container
WORKDIR /app


RUN yarn global add webpack

RUN yarn install

RUN chown -R www-data:www-data /app


# Set the command to run for the asset service
EXPOSE 3000

CMD ["npm", "start"]