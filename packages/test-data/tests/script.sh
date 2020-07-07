#!/bin/bash

echo "Running tests(8889) scripts"

echo "Setting up database..."
wp db query < wp-content/plugins/data/tests/data.sql

echo "Copying files..."
cp -r wp-content/plugins/data/uploads wp-content
