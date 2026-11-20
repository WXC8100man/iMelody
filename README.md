# iMelody
iMelody (Mediawiki Extension) is an Mediawiki extension for parses and plays iMelody (.imy) ringtone objects using {{#imy:}} parser function and an embedded synth player.

## Requirements

* **MediaWiki:** `>= 1.39.0`
* **PHP:** `>= 7.4` or `>= 8.0` (matching your MediaWiki 1.39+ requirement)
* **Required Extensions:** None (Standalone parser hook; works alongside standard MediaWiki skins and VisualEditor).

## Installation

### Method 1: Manual Installation
1. Download or clone the extension files into your MediaWiki `extensions/` directory so the path is `extensions/iMelody`.
2. Ensure your file structure looks like this:
   ```iMelody/
├── .gitignore
├── composer.json
├── extension.json
├── README.md
├── includes/
│   └── IMYParser.php
└── resources/
    └── imy-player.js
   ```
3. Add the following line to the bottom of your `LocalSettings.php` file:
   ```php
   wfLoadExtension( 'iMelody' );
   ```

### Method 2: Using Composer
If you have published this extension to Packagist or added it as a local/VCS repository in your root MediaWiki `composer.local.json`:

1. Create a `composer.json` inside `extensions/iMelody/`:
   ```json
   {
  "name": "iMelody",
  "version": "1.0",
  "author": “Vcrelt373246”,
  "license-name": "GPL-2.0-or-later",
  "description": "Parses and plays iMelody (.imy) ringtone objects using {{#imy:}}.",
  "type": "parserhook",
  "requires": {
    "MediaWiki": ">= 1.39.0"
  },
  "AutoloadClasses": {
    "IMY\\IMYParser": "includes/IMYParser.php"
  },
  "Hooks": {
    "ParserFirstCallInit": "IMY\\IMYParser::onParserFirstCallInit"
  },
  "manifest_version": 2
}
   ```
2. Run Composer from your root MediaWiki directory:
   ```bash
   composer require vcrelt373246/mediawiki-imelody
   ```
3. Enable the extension in `LocalSettings.php`:
   ```php
   wfLoadExtension( 'iMelody' );
   ```

## Usage

Use the `{{#imy:}}` parser function on any wiki page:
```wikitext
{{#imy:Test}}
```

## Verification (Done & Installed Check)

1. Navigate to **`Special:Version`** on your wiki.
2. Scroll down to the **Installed extensions** table under the *Parser hooks* section.
3. Confirm that **iMelody (Version 1.0)** by *Vcrelt373246* is listed.

## Troubleshooting & Fixes

* **Extension does not appear on `Special:Version`:**
  * Verify the folder name is exactly `iMelody` (case-sensitive on Linux servers) matching `"name": "iMelody"` in `extension.json`.
* **`Class 'IMY\IMYParser' not found` error:**
  * Check that `includes/IMYParser.php` starts with `namespace IMY;` at the top of the file and matches the `"AutoloadClasses"` path in `extension.json`.
* **Parser function renders as plain text `{{#imy:...}}`:**
  * Clear your wiki's parser cache or purge the page (`?action=purge`), and verify `ParserFirstCallInit` is properly registering the `imy` magic word.
 * and other, if you issues/error, please fix!

### Composer

#### Use composer.json
Create a `composer.json` inside `extensions/iMelody/`:
   ```json
   {
       "name": "vcrelt373246/mediawiki-imelody",
       "type": "mediawiki-extension",
       "description": "Parses and plays iMelody (.imy) ringtone objects using {{#imy:}}.",
       "license": "GPL-2.0-or-later",
       "require": {
           "composer/installers": "^1.0 || ^2.0"
       },
       "autoload": {
           "psr-4": {
               "IMY\\": "includes/"
           }
       }
   }
   ```

#### Step 1: Navigate to MediaWiki Root & Download Composer
Open **Git Bash** and run:
```bash
cd "/d/naufal/aku/app/XAMPP/htdocs/mywikitiki"

# Download composer.phar using XAMPP's PHP
/d/naufal/aku/app/XAMPP/php/php.exe -r "readfile('https://getcomposer.org');" | /d/naufal/aku/app/XAMPP/php/php.exe
```

#### Step 2: Create `composer.local.json`
Still inside `/d/naufal/aku/app/XAMPP/htdocs/mywikitiki`, create `composer.local.json` so MediaWiki merges your extension's `composer.json`:
```bash
cat << 'EOF' > composer.local.json
{
    "extra": {
        "merge-plugin": {
            "include": [
                "extensions/*/composer.json",
                "skins/*/composer.json"
            ]
        }
    }
}
EOF
```

#### Step 3: Run Composer Update
Run `composer.phar` using XAMPP's PHP:
```bash
/d/naufal/aku/app/XAMPP/php/php.exe composer.phar update --no-dev
```

*(Optional)* To make `composer` work anywhere in Git Bash without typing the full path, create an alias:
```bash
echo 'alias composer="/d/naufal/aku/app/XAMPP/php/php.exe /d/naufal/aku/app/XAMPP/htdocs/mywikitiki/composer.phar"' >> ~/.bashrc
source ~/.bashrc
```

## License

This extension is licensed under the **GPL-2.0-or-later** license. See the `LICENSE` file for details.

