#!/usr/bin/env bash
#
# Compare the output of the current code with a 1.1 release.
#
# Usage: .docker/compare-with-1.1.sh [TAG]   default: newest v1.1.* tag
#
# Runs output-matrix.php (a large set of inputs for the public API, including
# invalid ones) against the working tree and against the given tag, both on
# PHP 8.6 in Docker, and prints the differences. 2.0 must produce the same
# output as 1.1 except for documented bug fixes.
#
# Requirements on the host: bash, docker, composer, git, rsync.

set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TAG="${1:-$(git -C "$PROJECT_DIR" tag --list 'v1.1.*' --sort=-v:refname | head -n 1)}"
DOCKER_REGISTRY="${DOCKER_REGISTRY:-mirror.gcr.io}"
# PHP 8.6 is not released yet, so its release candidate image is used.
IMAGE="$DOCKER_REGISTRY/library/php:8.6-rc-cli"
WORKDIR="$PROJECT_DIR/build/compare"

rm -rf "$WORKDIR"
mkdir -p "$WORKDIR/current" "$WORKDIR/$TAG"
rsync -a \
    --exclude '/.git' --exclude '/.idea' --exclude '/.tools' \
    --exclude '/build' --exclude '/vendor' --exclude '/composer.lock' \
    "$PROJECT_DIR/" "$WORKDIR/current/"
git -C "$PROJECT_DIR" archive "$TAG" | tar -x -C "$WORKDIR/$TAG"

for dir in current "$TAG"; do
    composer --working-dir="$WORKDIR/$dir" dump-autoload --quiet
    docker run --rm -v "$WORKDIR:/compare" -v "$PROJECT_DIR/.docker:/scripts:ro" "$IMAGE" \
        php /scripts/output-matrix.php "/compare/$dir" > "$WORKDIR/$dir.jsonl"
done

cases="$(wc -l < "$WORKDIR/current.jsonl" | tr -d ' ')"
if diff "$WORKDIR/$TAG.jsonl" "$WORKDIR/current.jsonl" > "$WORKDIR/diff.txt"; then
    echo "Output identical to $TAG ($cases cases)."
else
    cat "$WORKDIR/diff.txt"
    echo "Output differs from $TAG, see above (< $TAG, > current; $cases cases)."
    exit 1
fi
