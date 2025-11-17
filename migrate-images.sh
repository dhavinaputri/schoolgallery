#!/bin/bash

# Script untuk migrasi gambar existing ke Railway
# Jalankan script ini SETELAH deploy pertama di Railway

echo "Migrating existing images to Railway storage..."

# Buat direktori target jika belum ada
mkdir -p /app/storage/app/public/galleries
mkdir -p /app/storage/app/public/news
mkdir -p /app/storage/app/public/avatars
mkdir -p /app/storage/app/public/teachers
mkdir -p /app/storage/app/public/submissions

# Copy gambar dari storage lama ke Railway storage dengan preserving subfolder structure
for source_dir in galleries news avatars teachers submissions; do
    if [ -d "storage/app/public/$source_dir" ]; then
        echo "Copying $source_dir (including subdirectories)..."
        
        # Create all subdirectories first
        find storage/app/public/$source_dir -type d | while read subdir; do
            target_subdir="${subdir/storage\/app\/public/\/app\/storage\/app\/public}"
            mkdir -p "$target_subdir" 2>/dev/null || true
        done
        
        # Copy all files preserving directory structure
        cp -r storage/app/public/$source_dir/* /app/storage/app/public/$source_dir/ 2>/dev/null || true
        echo "✅ $source_dir migrated"
    fi
done

# Set permissions
chmod -R 755 /app/storage/app/public

echo "Image migration completed!"
echo "Total files migrated:"
find /app/storage/app/public -type f | wc -l
echo ""
echo "Directory structure:"
find /app/storage/app/public -type d | sort
