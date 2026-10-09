param([switch]$Produccion)
$ErrorActionPreference = 'Stop'
$pagina = $PSScriptRoot
$xampp = [IO.Path]::GetFullPath((Join-Path $pagina '..\..\..'))
$backend = Join-Path $pagina 'mi-proyecto-strapi'
$storage = Join-Path $pagina 'storage'
$nodeCandidates = @(
    'D:\apps\nodejs\node-v24.19.0-win-x64\node.exe',
    (Join-Path $env:ProgramFiles 'nodejs\node.exe')
)
$nodeCommand = Get-Command node -ErrorAction SilentlyContinue
if ($nodeCommand) { $nodeCandidates += $nodeCommand.Source }
$node = $nodeCandidates | Where-Object { Test-Path -LiteralPath $_ } | Select-Object -First 1
if (!$node) { throw 'Instalar Node.js 24 antes de iniciar Strapi.' }
$npmCli = Join-Path (Split-Path $node -Parent) 'node_modules\npm\bin\npm-cli.js'
if (!(Test-Path -LiteralPath $npmCli)) { throw 'No se encontró npm junto a Node.js.' }
$env:Path = (Split-Path $node -Parent) + ';' + $env:Path
if (!(Test-Path -LiteralPath (Join-Path $backend 'node_modules\@strapi\strapi'))) {
    throw 'Primero ejecutar npm ci en mi-proyecto-strapi.'
}
$services = @(
    @{Name='mysqld'; Exe=(Join-Path $xampp 'mysql\bin\mysqld.exe'); Args=@("--defaults-file=$xampp\mysql\bin\my.ini",'--standalone')},
    @{Name='httpd'; Exe=(Join-Path $xampp 'apache\bin\httpd.exe'); Args=@()}
)
foreach ($service in $services) {
    if (!(Test-Path -LiteralPath $service.Exe)) { throw "No existe $($service.Exe). Iniciar XAMPP manualmente." }
    $running = Get-Process -Name $service.Name -ErrorAction SilentlyContinue | Where-Object { $_.Path -eq $service.Exe }
    if (!$running) {
        $launch = @{FilePath=$service.Exe; WorkingDirectory=$xampp; WindowStyle='Hidden'}
        if ($service.Args.Count) { $launch.ArgumentList = $service.Args }
        Start-Process @launch
    }
}
$ready = $false
for ($attempt=0; $attempt -lt 30; $attempt++) {
    if (Get-NetTCPConnection -LocalPort 3306 -State Listen -ErrorAction SilentlyContinue) { $ready=$true; break }
    Start-Sleep -Seconds 1
}
if (!$ready) { throw 'MySQL no inició en el puerto 3306. Revisar XAMPP.' }
if (!(Get-NetTCPConnection -LocalPort 1337 -State Listen -ErrorAction SilentlyContinue)) {
    $mode = if ($Produccion) { 'start' } else { 'develop' }
    $launchArguments = '"' + $npmCli + '" run ' + $mode
    Start-Process -FilePath $node -ArgumentList $launchArguments -WorkingDirectory $backend -WindowStyle Hidden -RedirectStandardOutput (Join-Path $storage 'strapi.stdout.log') -RedirectStandardError (Join-Path $storage 'strapi.stderr.log')
}
Write-Host 'Strapi iniciándose. Panel: http://127.0.0.1:1337/admin'
Write-Host 'Tienda: http://localhost:8080/chatgptNube/pagina/ (adaptar el puerto si tu Apache usa otro).'
Write-Host 'Logs: pagina/storage/strapi.stdout.log y strapi.stderr.log'