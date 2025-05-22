#!/bin/bash

# === Variables ===
DATE=$(date +"%Y-%m-%d_%H-%M")
DOSSIER_SAUVEGARDE="/var/www/sauvegardes"
DOSSIER_PROJET="/var/www/html/studiosdb"
NOM_ARCHIVE="studiosdb_fichiers_$DATE.tar.gz"
NOM_DUMP="studiosdb_db_$DATE.sql"
NOM_ARCHIVE_DB="studiosdb_db_$DATE.sql.gz"
UTILISATEUR_MYSQL="root"
BASE_DE_DONNEES="studiosdb"

# === Création dossier de sauvegarde ===
mkdir -p $DOSSIER_SAUVEGARDE

# === Sauvegarde fichiers Laravel ===
echo "📦 Sauvegarde du projet Laravel..."
tar -czf $DOSSIER_SAUVEGARDE/$NOM_ARCHIVE $DOSSIER_PROJET

# === Sauvegarde base de données ===
echo "💾 Sauvegarde de la base de données..."
mysqldump -u $UTILISATEUR_MYSQL -p $BASE_DE_DONNEES > $DOSSIER_SAUVEGARDE/$NOM_DUMP

# Compression SQL
gzip $DOSSIER_SAUVEGARDE/$NOM_DUMP

# === Résultat ===
echo "✅ Sauvegardes terminées !"
echo "➡️  Fichiers : $DOSSIER_SAUVEGARDE/$NOM_ARCHIVE"
echo "➡️  Base de données : $DOSSIER_SAUVEGARDE/$NOM_ARCHIVE_DB"
