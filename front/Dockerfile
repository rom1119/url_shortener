# Base image
FROM node:16

# Set the working directory inside the container
WORKDIR /app

ENV PATH /app/node_modules/.bin:$PATH

# RUN yarn global add webpack
RUN yarn install
RUN yarn global add react-scripts@3.4.1 

COPY ./front/app/ /app
# RUN chown -R www-data:www-data /app


# Set the command to run for the asset service
EXPOSE 3000

CMD ["npm", "start"]