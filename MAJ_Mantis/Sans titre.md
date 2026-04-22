#Start with a base image containing Java runtime
FROM eclipse temurin 17 jdk
#Information around who maintains the image
LABEL org opencontainers image authors "="hilala com
#Add the application's jar to the image
COPY target /department microservice 0 0 1 SNAPSHOT jar department
microservice 0 0 1 
SNAPSHOT jar
#execute the application
ENTRYPOINT
["java", jar", "department microservice 0.0.1
SNAPSHOT.jar"]