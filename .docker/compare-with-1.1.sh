#!/usr/bin/env bash
#
# Compare the output of the current code with a 1.1 release.
#
# Usage: .docker/compare-with-1.1.sh [TAG]   default: newest v1.1.* tag
#
# Runs output-matrix.php (a large set of inputs for the public API, including
# invalid ones) against the working tree and against the given tag, both on
# PHP 8.6 in Docker, with and without the calendar extension (Erister uses
# easter_days() if available), and prints the differences. 2.0 must produce
# the same output as 1.1 except for documented bug fixes.
#
# Requirements on the host: bash, docker, composer, git, rsync.

set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TAG="${1:-$(git -C "$PROJECT_DIR" tag --list 'v1.1.*' --sort=-v:refname | head -n 1)}"
DOCKER_REGISTRY="${DOCKER_REGISTRY:-mirror.gcr.io}"
# PHP 8.6 is not released yet, so its release candidate image is used.
IMAGE="$DOCKER_REGISTRY/library/php:8.6-rc-cli"
IMAGE_CALENDAR="ddate-test/php:8.6-rc-calendar"
WORKDIR="$PROJECT_DIR/build/compare"

docker build --quiet --tag "$IMAGE_CALENDAR" - > /dev/null <<DOCKERFILE
FROM $IMAGE
RUN docker-php-ext-install calendar
DOCKERFILE

rm -rf "$WORKDIR"
mkdir -p "$WORKDIR/current" "$WORKDIR/$TAG"
rsync -a \
    --exclude '/.git' --exclude '/.idea' --exclude '/.tools' \
    --exclude '/build' --exclude '/vendor' --exclude '/composer.lock' \
    "$PROJECT_DIR/" "$WORKDIR/current/"
git -C "$PROJECT_DIR" archive "$TAG" | tar -x -C "$WORKDIR/$TAG"

failed=""
for dir in current "$TAG"; do
    composer --working-dir="$WORKDIR/$dir" dump-autoload --quiet
done
for variant in plain calendar; do
    image="$IMAGE"
    [ "$variant" = calendar ] && image="$IMAGE_CALENDAR"
    for dir in current "$TAG"; do
        docker run --rm -v "$WORKDIR:/compare" -v "$PROJECT_DIR/.docker:/scripts:ro" "$image" \
            php /scripts/output-matrix.php "/compare/$dir" > "$WORKDIR/$dir-$variant.jsonl"
    done
    cases="$(wc -l < "$WORKDIR/current-$variant.jsonl" | tr -d ' ')"
    if diff "$WORKDIR/$TAG-$variant.jsonl" "$WORKDIR/current-$variant.jsonl" > "$WORKDIR/diff-$variant.txt"; then
        echo "Output identical to $TAG ($cases cases, $variant)."
    else
        cat "$WORKDIR/diff-$variant.txt"
        echo "Output differs from $TAG ($variant), see above (< $TAG, > current; $cases cases)."
        failed=1
    fi
done
[ -z "$failed" ]
