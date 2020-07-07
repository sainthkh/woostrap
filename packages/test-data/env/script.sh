#!/bin/bash

echo "Running env(8888) script"

echo "Setting up database..."
wp db query < wp-content/plugins/data/env/data.sql

echo "Copying files..."
cp -r wp-content/plugins/data/uploads wp-content
