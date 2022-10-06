# syntax=docker/dockerfile:1

# Inherit from Usabilla dev image, which already has xdebug and its configuration.
# All necessary global configurations, extensions and modules
# should be put here
FROM php:5.5.38-fpm AS base

# When composer gets copied we want to make sure it's from the major version 2
FROM composer:2 as composer

# The source target is responsible to prepare the source code by cleaning it and
# installing the necessary dependencies, it's later copied into the production
# target, which then leaves no traces of the build process behind whilst making
# the image lean
FROM base as source

ENV COMPOSER_HOME=/opt/.composer

RUN apk add --no-cache git

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /opt/archived

# hadolint ignore=SC2215
RUN --mount=type=bind,source=./,rw,target=./ \
    mkdir -p /opt/project \
    && git archive --verbose --format tar HEAD | tar -x -C /opt/project

WORKDIR /opt/project

# hadolint ignore=SC2215
RUN --mount=type=bind,source=.composer/cache,target=/opt/.composer/cache \
    composer install --no-interaction --no-progress --no-dev --prefer-dist --classmap-authoritative

# Copy the source from its target and prepare permissions
FROM base as prod

WORKDIR /opt/project

COPY --chown=app:app --from=source /opt/project /opt/project

# Install Xdebug and enable development specific configuration
# also create a volume for the project which will later be mount via run
FROM base AS dev

COPY --chown=app:app --from=composer /usr/bin/composer /usr/local/bin/composer

VOLUME [ "/opt/project" ]
