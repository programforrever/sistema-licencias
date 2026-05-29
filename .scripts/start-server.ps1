<#
Start-server.ps1

Uso: Ejecutar desde PowerShell en la raíz del proyecto (o doble click):
  .\.scripts\start-server.ps1

Qué hace:
 - Busca procesos escuchando en los puertos listados (8000,5173,3000,8080,6001)
 - Mata esos procesos (si el usuario lo permite)
 - Inicia el servidor PHP integrado apuntando a la carpeta `public` en 127.0.0.1:8000

Nota: requiere permisos para terminar procesos y que `php` esté en PATH (por ejemplo, XAMPP PHP).
#>

Param(
    [int[]]$Ports = @(8000,5173,3000,8080,6001),
    [string]$Host = '127.0.0.1',
    [int]$Port = 8000
)

Write-Host "Stopping any processes listening on ports: $($Ports -join ', ')" -ForegroundColor Cyan

$toKill = @()
foreach ($p in $Ports) {
    $conns = Get-NetTCPConnection -State Listen -ErrorAction SilentlyContinue | Where-Object { $_.LocalPort -eq $p }
    foreach ($c in $conns) {
        if ($c.OwningProcess) { $toKill += [PSCustomObject]@{ Port = $p; PID = $c.OwningProcess } }
    }
}

if ($toKill.Count -gt 0) {
    $toKill | Format-Table -AutoSize
    $confirm = Read-Host "Kill these processes? (Y/n)"
    if ($confirm -eq 'n' -or $confirm -eq 'N') {
        Write-Host "Aborted by user." -ForegroundColor Yellow
        exit 1
    }

    foreach ($row in $toKill) {
        try {
            Stop-Process -Id $row.PID -Force -ErrorAction Stop
            Write-Host "Stopped PID $($row.PID) (port $($row.Port))" -ForegroundColor Green
        } catch {
            Write-Host "Failed to stop PID $($row.PID): $_" -ForegroundColor Red
        }
    }
} else {
    Write-Host "No listening processes found on those ports." -ForegroundColor Green
}

# Start PHP built-in server in the public folder
$publicPath = Join-Path -Path (Get-Location) -ChildPath 'public'
if (-not (Test-Path $publicPath)) {
    Write-Host "public folder not found at $publicPath" -ForegroundColor Red
    exit 1
}

Write-Host "Starting PHP built-in server at http://$Host:$Port (public folder: $publicPath)" -ForegroundColor Cyan
Start-Process -NoNewWindow -FilePath php -ArgumentList "-S $Host`:$Port -t \"$publicPath\"" -WorkingDirectory $publicPath
Write-Host "Server started. Open http://$Host`:$Port in your browser." -ForegroundColor Green
