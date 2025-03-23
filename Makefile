# Makefile for managing Docker and Laravel setup

include .env

# Variables
DOCKER_COMPOSE = docker compose
DOCKER = docker
WP_CLI = $(DOCKER_COMPOSE) run --rm wp-cli 
WP_THEME = $(WP_CLI) theme
THEME =  kiosko
ADMIN_EMAIL = sr.avila.g@gmail.com


export $(shell sed 's/=.*//' .env) 


# Commands

# Build the Docker containers
build:
	$(DOCKER_COMPOSE) up -d --build
# docker compose run --rm wp-cli theme activate shop
wp-theme-activate:
	$(WP_THEME) activate $(theme)
up:
	$(DOCKER_COMPOSE) up -d
init:
	$(DOCKER_COMPOSE) up -d --build
	$(WP_THEME) activate $(THEME)

install:
	$(WP_CLI) core install --url=$(WP_URL) --title=$(WP_TITLE) --admin_user=$(WP_ADMIN_USER) --admin_password=$(WP_ADMIN_PASSWORD) --admin_email=$(ADMIN_EMAIL)

# Destroy the Docker containers and volumes
destroy:
	$(DOCKER_COMPOSE) down --volumes