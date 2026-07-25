<#
PowerShell helper to install Node.js (via winget or choco) if missing,
then run npm install && npm run build in the project and clear Laravel caches.
Run as Administrator if using winget/choco.
#>

param(
    [string]$ProjectPath = "C:\laragon\www\cerape",
    [string]$PhpCli = "C:\laragon\bin\php\php-8.4.12-nts-Win32-vs17-x64\php.exe",
    [int]$WaitForNpmTimeoutSeconds = 60
)

function Write-err([string]$m){ Write-Host "[ERROR] $m" -ForegroundColor Red }
function Write-ok([string]$m){ Write-Host "[OK] $m" -ForegroundColor Green }
function Write-info([string]$m){ Write-Host "[INFO] $m" -ForegroundColor Cyan }

Set-Location -Path $ProjectPath

Write-info "Checking for npm..."
try {
    $npmVer = npm --version 2>$null
} catch {
    $npmVer = $null
}

if (-not $npmVer) {
    Write-info "npm not found. Trying to install Node.js..."

    $winget = Get-Command winget -ErrorAction SilentlyContinue
    $choco = Get-Command choco -ErrorAction SilentlyContinue

    if ($winget) {
        Write-info "Installing Node.js LTS via winget (requires admin)..."
        try {
            winget install --id OpenJS.NodeJS.LTS -e --accept-source-agreements --accept-package-agreements
        } catch {
            Write-err "winget install failed: $_"
        }
    } elseif ($choco) {
        Write-info "Installing Node.js LTS via Chocolatey (requires admin)..."
        try {
            choco install nodejs-lts -y
        } catch {
            Write-err "choco install failed: $_"
        }
    } else {
        Write-err "Neither winget nor choco found. Please install Node.js manually from https://nodejs.org/ and re-run this script."
        exit 2
    }

    # Wait for npm to appear in PATH
    Write-info "Waiting up to $WaitForNpmTimeoutSeconds seconds for npm to become available..."

    $start = Get-Date
    while ((Get-Date) - $start -lt (New-TimeSpan -Seconds $WaitForNpmTimeoutSeconds)) {
        Start-Sleep -Seconds 2
        try { $npmVer = npm --version 2>$null } catch { $npmVer = $null }
        if ($npmVer) { break }
    }

    if (-not $npmVer) {
        Write-err "npm still not available after install. Please re-open your terminal or log off/login and re-run this script."
        exit 3
    }
}

Write-ok "npm available: $npmVer"

# Ensure node_modules installed and build
Write-info "Running npm install (or npm ci if package-lock exists)..."
if (Test-Path package-lock.json) {
    $installCmd = "npm ci"
} else {
    $installCmd = "npm install"
}

$installExit = & cmd /c $installCmd
if ($LASTEXITCODE -ne 0) {
    Write-err "npm install failed (exit $LASTEXITCODE). Output above."
    exit $LASTEXITCODE
}
Write-ok "npm dependencies installed."

Write-info "Running npm run build..."
$buildExit = & cmd /c "npm run build"
if ($LASTEXITCODE -ne 0) {
    Write-err "npm run build failed (exit $LASTEXITCODE). Output above."
    exit $LASTEXITCODE
}
Write-ok "npm run build completed."

# Run artisan optimize:clear
if (-not (Test-Path $PhpCli)) {
    Write-err "PHP CLI not found at $PhpCli. Adjust the script parameter -PhpCli to point to your PHP executable."
    exit 4
}

Write-info "Running artisan optimize:clear..."
& "$PhpCli" artisan optimize:clear
if ($LASTEXITCODE -ne 0) {
    Write-err "artisan optimize:clear failed (exit $LASTEXITCODE)."
    exit $LASTEXITCODE
}
Write-ok "artisan optimize:clear finished."

Write-ok "Build flow complete. Please open http://cerape.test/admin/login and verify styling."
