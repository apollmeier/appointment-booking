# Terminbuchungssystem

Eine Laravel-API für die Verwaltung von Terminen mit JSON:API-Spezifikation, E-Mail-Benachrichtigungen und umfassenden
Tests.

## 🚀 Schnellstart

### Voraussetzungen

- PHP 8.2+
- Composer
- SQLite mit PDO_SQLITE-Extension

### Installation

```bash
# Repository klonen
git clone https://github.com/apollmeier/appointment-booking
cd appointment-booking

# Abhängigkeiten installieren
composer install

# Umgebung konfigurieren
cp .env.example .env
php artisan key:generate

# Datenbank erstellen
touch database/database.sqlite

# Datenbank migrieren und mit Beispieldaten befüllen
php artisan migrate --seed

# Entwicklungsserver starten
php artisan serve
```

Die API ist nun unter `http://localhost:8000` erreichbar.

## 📋 API-Dokumentation

### Design-Prinzipien

- **JSON:API-Standard**: Konsistente API-Struktur nach [JSON:API-Spezifikation](https://jsonapi.org/)
- **Versionierung**: Sichere API-Evolution ohne Breaking Changes
- **Pagination**: Optimierte Performance durch paginierte Responses
- **E-Mail-Integration**: Automatische Benachrichtigungen bei Terminänderungen

### JSON:API-Implementation

Die API ist nach der JSON:API Spezifikation designt. Für Laravel gibt es das Package Laravel JSON:API, welches diese
komplett implementiert. Auf den Einsatz wurde hier bewusst verzichtet, um den Umgang mit API-Ressourcen und deren
Funktionsweise zu demonstrieren.

### Endpunkte testen

Das Projekt enthält eine vorkonfigurierte [Bruno](https://www.usebruno.com/)-Kollektion im Ordner `.bruno/`. Bruno ist
eine Open-Source-API-IDE ohne Account-Zwang.

**Setup:**

1. Bruno herunterladen und installieren
2. Kollektion aus dem `.bruno/`-Ordner öffnen
3. Alle API-Endpunkte sind sofort testbereit

## 📧 E-Mail-Konfiguration

**Entwicklungsmodus:** E-Mails werden standardmäßig in `storage/logs/laravel.log` protokolliert.

## 🧪 Tests

Das Projekt verwendet Feature-Tests für End-to-End-Validierung der API-Funktionalität:

```bash
# Alle Tests ausführen
php artisan test
```

**Test-Abdeckung:**

- TimeSlot-Endpunkte und -Validierung
- Appointment-Erstellung und -Stornierung
- JSON-Struktur-Validierung

## 🏗️ Architektur

### Bewusste Design-Entscheidungen

**Controller-fokussierter Ansatz:** Auf Service-Layer-Abstraktion wurde bewusst verzichtet, da die Anwendung
überschaubar ist und die Controller gut lesbar bleiben.

**Skalierbarkeit:** Bei wachsender Komplexität können Funktionen später in Services abstrahiert werden, um die
Controller zu verschlanken.

### Projektstruktur

```
├── app/
│   ├── Http/Controllers/Api/V1/  # API-Controller
│   ├── Http/Requests/V1          # Request mit Validierung 
│   ├── Http/Resources/V1         # JSON-API-Ressourcen  
│   ├── Models/                   # Eloquent-Models
│   └── Mail/                     # E-Mail-Templates
├── database/
│   ├── migrations/               # Datenbankmigrationen
│   └── seeders/                  # Beispieldaten
├── tests/Feature/                # Feature-Tests
└── .bruno/                       # API-Testkollektionen
```
