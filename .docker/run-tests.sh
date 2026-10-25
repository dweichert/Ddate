#!/usr/bin/env bash
#
# Run the test suite against every supported PHP version using Docker.
#
# Usage: .docker/run-tests.sh [PHP_VERSION ...]   e.g. .docker/run-tests.sh 8.6
#
# Dependencies are resolved on the host with Composer, using a platform
# override for each target PHP version. PHPUnit itself then runs inside a
# container providing that PHP version. Each version gets its own working
# copy below build/test-php-<version>/, so the project's own vendor/ is left
# untouched.
#
# Requirements on the host: bash, docker, composer, rsync.

set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ALL_VERSIONS="8.6"
VERSIONS="${*:-$ALL_VERSIONS}"

# Images are pulled through Google's Docker Hub mirror by default to avoid
# Docker Hub's rate limit for anonymous pulls. Use DOCKER_REGISTRY=docker.io
# to pull from Docker Hub directly.
DOCKER_REGISTRY="${DOCKER_REGISTRY:-mirror.gcr.io}"

image_for() {
    echo "ddate-test/php:$1"
}

# The official php images for 8.6 no longer include mbstring, which PHPUnit
# needs; build a local image with it (cached by Docker after the first run).
# PHP 8.6 is not released yet, so its release candidate image is used.
build_image() {
    local base="$DOCKER_REGISTRY/library/php:$1-cli"
    case "$1" in
        8.6) base="$DOCKER_REGISTRY/library/php:8.6-rc-cli" ;;
    esac
    docker build --quiet --tag "$(image_for "$1")" - > /dev/null <<DOCKERFILE
FROM $base
RUN apt-get update && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install mbstring && rm -rf /var/lib/apt/lists/*
DOCKERFILE
}

failed=""

for version in $VERSIONS; do
    echo "=== PHP $version ==="
    build_image "$version"
    workdir="$PROJECT_DIR/build/test-php-$version"
    mkdir -p "$workdir"
    rsync -a --delete \
        --exclude '/.git' --exclude '/.idea' --exclude '/.tools' \
        --exclude '/build' --exclude '/vendor' --exclude '/composer.lock' \
        --exclude '/.phpunit.cache' --exclude '/.phpunit.result.cache' \
        "$PROJECT_DIR/" "$workdir/"

    (
        cd "$workdir"
        composer config platform.php "$version.99"
        composer update --no-interaction --no-progress --quiet
    )

    if docker run --rm --entrypoint php -v "$workdir:/app" -w /app "$(image_for "$version")" \
        -r 'echo "PHP ", PHP_VERSION, PHP_EOL; passthru("php vendor/phpunit/phpunit/phpunit", $code); passthru("php bin/ddate \"%{%A, %B %d,%} %Y YOLD\" 29022012", $c2); echo PHP_EOL; exit($code || $c2 ? 1 : 0);'
    then
        echo "=== PHP $version: OK ==="
    else
        echo "=== PHP $version: FAILED ==="
        failed="$failed $version"
    fi
done

if [ -n "$failed" ]; then
    echo "Failed PHP versions:$failed"
    exit 1
fi
echo "All PHP versions passed: $VERSIONS"
