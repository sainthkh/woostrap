#!/bin/bash

echo "Running env(8888) script"

echo "Setting up database..."
wp db query < wp-content/plugins/test-data/env/data.sql

echo "Copying files..."
cp -r wp-content/plugins/test-data/uploads wp-content
