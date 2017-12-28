FROM composer AS builder

COPY . /app
RUN  composer update --no-dev --ignore-platform-reqs --no-interaction --no-progress -a -d /app

FROM alpine:latest

ARG FAAS_RELEASE=0.6.15
ENV fprocess "php index.php"

WORKDIR /app
EXPOSE 8080
CMD ["fwatchdog"]

HEALTHCHECK --interval=1s CMD [ -e /tmp/.lock ] || exit 1

RUN apk --no-cache add curl php7 php7-json php7-curl && \
    curl -sSL "https://github.com/openfaas/faas/releases/download/$FAAS_RELEASE/fwatchdog" > /usr/bin/fwatchdog && \
    chmod +x /usr/bin/fwatchdog

COPY --from=builder /app /app
