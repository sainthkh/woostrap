#!/bin/bash

echo "Running tests(8889) scripts"

echo "Setting up database..."
wp db query < wp-content/plugins/test-data/tests/data.sql

echo "Copying files..."
cp -r wp-content/plugins/test-data/uploads wp-content
