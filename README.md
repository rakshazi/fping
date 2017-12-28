# fping

Simple microservice / [function](https://openfaas.com) to check target server, site, etc status.

## Table of Contents
<!-- vim-markdown-toc GFM -->

* [Usage](#usage)
    - [Docker](#docker)
    - [OpenFaaS](#openfaas)
    - [Composer (optional)](#composer-optional)
    - [API](#api)
        + [Request](#request)
        + [Response](#response)
* [Check types](#check-types)
    - [HTTP](#http)

<!-- vim-markdown-toc -->

## Usage

### Docker

```bash
docker run -d -p 8080:8080 rakshazi/fping

curl -X POST -d "type=HTTP&address=https://google.com" localhost:8080

#response
{"status_code":200,"time":0.448615,"should_contain":true,"should_not_contain":true,"status":true}
```

### OpenFaaS

Coming soon...

### Composer (optional)

If you want to use fping "as-is", without docker, you can install it with composer:

```bash
composer require rakshazi/fping
```

### API

#### Request

fping built on top of [OpenFaaS Watchdog](https://openfaas.com) to provide simple API and integration with OpenFaaS.

To call fping, you should send **POST** request to fping address (in example above - localhost:8080) with following body structure:

**json**:

```json
{
    "type": "Check type",
    "address": "Target server/site address",
    "timeout": 5, //integer timeout in seconds
    "optional": {} //optional params, specific for each check type
}
```

**raw form**:

```
type=Check type&address=Target address&timeout=5&optional[param1]=val1&optional[param2]=val2
```

#### Response

**json**:

```json
{
    "status_code":200, //HTTP response status code
    "time":448, //response time in ms
    "should_contain":true, //optional for HTTP
    "should_not_contain":true, //optional for HTTP
    "status":true //check status - is target up or down
}
```

## Check types

### HTTP

Check HTTP(-s) targets.

**optional params**:
 * `should_contain` - Check response body for that string, check passed if string was found
 * `should_not_contain` - Check response body for that string, check failed if string was found

Example:

```bash
curl -X POST -d "type=HTTP&address=https://example.com&timeout=5&optional[should_contain]=healthy&optional[should_not_contain]=error" localhost:8080
```
