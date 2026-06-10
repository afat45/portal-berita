# ============================================
# Install PostgreSQL Extension for PHP
# Windows PowerShell Script
# ============================================

Write-Host "=========================================="
Write-Host "  Installing PostgreSQL Extension"
Write-Host "==========================================" -ForegroundColor Green
Write-Host ""

# Step 1: Get PHP version
Write-Host "[1/5] Getting PHP version..." -ForegroundColor Cyan
$php_version = php -v | Select-Object -First 1
Write-Host "PHP Version: $php_version" -ForegroundColor Yellow
Write-Host ""

# Get PHP folder
$php_folder = php -r "echo dirname(php_sapi_name() == 'cli' ? PHP_EXECUTABLE : get_cfg_var('cfg_file_path'));"
$php_path = (Get-Command php.exe).Source | Split-Path -Parent
$php_ext_folder = Join-Path (Split-Path $php_path -Parent) "ext"

Write-Host "PHP Folder: $php_path"
Write-Host "Extension Folder: $php_ext_folder"
Write-Host ""

# Step 2: Check if extension files exist
Write-Host "[2/5] Checking for extension files..." -ForegroundColor Cyan

$pdo_pgsql_exists = Test-Path "$php_ext_folder\php_pdo_pgsql.dll"
$pgsql_exists = Test-Path "$php_ext_folder\php_pgsql.dll"
$libpq_exists = Test-Path "$php_ext_folder\libpq.dll"

Write-Host "php_pdo_pgsql.dll: $(if ($pdo_pgsql_exists) { 'FOUND ✓' } else { 'NOT FOUND ✗' })" -ForegroundColor $(if ($pdo_pgsql_exists) { 'Green' } else { 'Red' })
Write-Host "php_pgsql.dll: $(if ($pgsql_exists) { 'FOUND ✓' } else { 'NOT FOUND ✗' })" -ForegroundColor $(if ($pgsql_exists) { 'Green' } else { 'Red' })
Write-Host "libpq.dll: $(if ($libpq_exists) { 'FOUND ✓' } else { 'NOT FOUND ✗' })" -ForegroundColor $(if ($libpq_exists) { 'Green' } else { 'Red' })
Write-Host ""

# Step 3: Get php.ini location
Write-Host "[3/5] Finding php.ini..." -ForegroundColor Cyan
$php_ini = php --ini | Select-String "Loaded Configuration File" | ForEach-Object { $_.ToString().Split(':')[1].Trim() }

if (Test-Path $php_ini) {
    Write-Host "php.ini found: $php_ini" -ForegroundColor Green
    Write-Host ""
    
    # Step 4: Check if already enabled
    Write-Host "[4/5] Checking if extensions are enabled..." -ForegroundColor Cyan
    
    $content = Get-Content $php_ini
    
    $pdo_pgsql_enabled = $content | Select-String "^extension=php_pdo_pgsql" | Measure-Object | Select-Object -ExpandProperty Count
    $pgsql_enabled = $content | Select-String "^extension=php_pgsql" | Measure-Object | Select-Object -ExpandProperty Count
    
    Write-Host "pdo_pgsql enabled: $(if ($pdo_pgsql_enabled -gt 0) { 'YES ✓' } else { 'NO ✗' })" -ForegroundColor $(if ($pdo_pgsql_enabled -gt 0) { 'Green' } else { 'Yellow' })
    Write-Host "pgsql enabled: $(if ($pgsql_enabled -gt 0) { 'YES ✓' } else { 'NO ✗' })" -ForegroundColor $(if ($pgsql_enabled -gt 0) { 'Green' } else { 'Yellow' })
    Write-Host ""
    
    if ($pdo_pgsql_enabled -gt 0 -and $pgsql_enabled -gt 0) {
        Write-Host "[5/5] Extensions already enabled! ✓" -ForegroundColor Green
        Write-Host ""
        Write-Host "Result: PostgreSQL support is READY!" -ForegroundColor Green
        Write-Host ""
        Write-Host "Next steps:"
        Write-Host "1. Restart Laragon (Stop All > Start All)"
        Write-Host "2. Run: php artisan config:clear"
        Write-Host "3. Test: php artisan tinker"
        Write-Host ""
    } else {
        Write-Host "[5/5] Extensions need to be enabled" -ForegroundColor Yellow
        Write-Host ""
        
        if ($pdo_pgsql_exists -and $pgsql_exists) {
            Write-Host "✓ DLL files are present. Just need to enable in php.ini" -ForegroundColor Green
            Write-Host ""
            Write-Host "Please add these lines to php.ini:" -ForegroundColor Cyan
            Write-Host "  extension=php_pdo_pgsql.dll"
            Write-Host "  extension=php_pgsql.dll"
            Write-Host ""
            Write-Host "Or visit: $php_ini" -ForegroundColor Yellow
        } else {
            Write-Host "✗ DLL files are MISSING" -ForegroundColor Red
            Write-Host ""
            Write-Host "You need to download PostgreSQL extension for PHP"
            Write-Host "Visit: https://windows.php.net/downloads/pecl/releases/"
            Write-Host ""
            Write-Host "Look for your PHP version and download:"
            Write-Host "- php_pdo_pgsql-x.x.x-x.x-vcx-x64.zip"
            Write-Host "- php_pgsql-x.x.x-x.x-vcx-x64.zip"
            Write-Host ""
            Write-Host "Extract and copy DLL files to: $php_ext_folder"
        }
    }
} else {
    Write-Host "ERROR: php.ini not found!" -ForegroundColor Red
}

Write-Host ""
Write-Host "=========================================="
Write-Host "  Script completed"
Write-Host "==========================================" -ForegroundColor Green
