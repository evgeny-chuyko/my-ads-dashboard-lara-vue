FROM nginx:alpine

# Copy custom nginx config
COPY conf.d/default.conf /etc/nginx/conf.d/default.conf

# Set working directory
WORKDIR /var/www/backend

# Expose port
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
