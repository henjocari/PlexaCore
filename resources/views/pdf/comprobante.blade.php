<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante de Egreso - {{ $manifiesto['manifiesto'] ?? '' }}</title>
    <style>
        @page {
            size: auto;
            margin: 10mm 15mm 10mm 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #30363B;
            margin: 0;
            padding: 0;
        }
        table { width: 100%; border-collapse: collapse; }
        .header-table { border-bottom: 3px solid #0878BE; padding-bottom: 15px; margin-bottom: 15px; }
        .company-info { text-align: right; color: #70777D; font-size: 10px; line-height: 1.4; }
        .company-name { color: #214E6B; font-size: 16px; font-weight: bold; margin-bottom: 3px; }
        .section-title { color: #214E6B; font-size: 11px; font-weight: bold; border-left: 4px solid #0878BE; padding-left: 6px; margin: 12px 0 6px; background: #F2F7FA; padding-top: 4px; padding-bottom: 4px; }
        .info-table td { padding: 5px 6px; border: 1px solid #CBD7DE; }
        .info-label { font-weight: bold; color: #70777D; background: #F2F7FA; width: 35%; text-transform: uppercase; font-size: 9px; }
        .liquidation-table th { background: #214E6B; color: #fff; padding: 6px; text-align: left; font-size: 9px; }
        .liquidation-table td { padding: 6px; border-bottom: 1px solid #e5eaed; }
        .money { text-align: right; font-weight: bold; }
        .totals-table td { padding: 10px; text-align: center; border: 1px solid #CBD7DE; border-radius: 4px; }
        .total-label { display: block; font-size: 8px; color: #70777D; font-weight: bold; margin-bottom: 3px; }
        .total-value { font-size: 13px; font-weight: bold; color: #214E6B; }
        .bg-saldo { background: #214E6B; } .bg-saldo .total-label, .bg-saldo .total-value { color: #fff; }
        .bg-final { background: #0878BE; } .bg-final .total-label, .bg-final .total-value { color: #fff; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="50%" valign="middle">
                <img src="{{ public_path('img/logo.png') }}" width="150" alt="PLEXA S.A.S">
            </td>
            <td width="50%" class="company-info" valign="middle">
                <div class="company-name">PLEXA S.A.S</div>
                NIT: 860515802-1<br>
                Calle 113 No. 7 -21 Oficina 903 Torre A<br>
                Bogotá, Colombia
            </td>
        </tr>
    </table>
 
    <h2 style="color: #214E6B; margin:0; font-size: 16px;">DETALLE DE LIQUIDACIÓN</h2>
 
    <table>
        <tr>
            <td width="48%" valign="top">
                <div class="section-title">DATOS DEL AFILIADO</div>
                <table class="info-table">
                    <tr><td class="info-label">Nombre / Razón Social</td><td>{{ $nombrePoseedor ?? $manifiesto['nombre'] ?? '' }}</td></tr>
                    <tr><td class="info-label">Identificación</td><td>{{ $manifiesto['idPoseedor'] ?? '' }}</td></tr>
                    <tr><td class="info-label">Conductor</td><td>{{ $manifiesto['conductor'] ?? '' }}</td></tr>
                    <tr><td class="info-label">Id Conductor</td><td>{{ $manifiesto['idConductor'] ?? '' }}</td></tr>
                </table>
            </td>
            <td width="4%"></td>
            <td width="48%" valign="top">
                <div class="section-title">DATOS PRINCIPALES</div>
                <table class="info-table">
                    <tr><td class="info-label">Manifiesto</td><td>{{ $manifiesto['manifiesto'] ?? '' }}</td></tr>
                    <tr><td class="info-label">Producto</td><td>{{ $manifiesto['producto'] ?? 'N/A' }}</td></tr>
                    <tr><td class="info-label">Placa</td><td>{{ $manifiesto['placa'] ?? '' }}</td></tr>
                    <tr><td class="info-label">Tráiler</td><td>{{ $manifiesto['trailer'] ?? '' }}</td></tr>
                    <tr><td class="info-label">Fecha</td><td>{{ $manifiesto['fecha'] ?? '' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>
 
    <div class="section-title">INFORMACIÓN DE CARGA</div>
    <table class="info-table">
        <tr>
            <td class="info-label" width="15%">Cantidad</td><td width="15%">{{ $manifiesto['cantidad'] ?? '0' }}</td>
            <td class="info-label" width="15%">Peso</td><td width="15%">{{ $manifiesto['peso'] ?? '0' }}</td>
            <td class="info-label" width="10%">Ruta</td><td>{{ $manifiesto['origen'] ?? '' }} - {{ $manifiesto['destino'] ?? '' }}</td>
        </tr>
    </table>
 
    <div class="section-title">DETALLE DE LIQUIDACIÓN</div>
    <table class="liquidation-table">
        <thead>
            <tr>
                <th width="25%">CONCEPTO</th>
                <th class="money" width="25%">VALOR</th>
                <th width="25%">CONCEPTO</th>
                <th class="money" width="25%">VALOR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Flete Neto</td>
                <td class="money">$ {{ number_format((float)($manifiesto['fleteNeto'] ?? 0), 0, ',', '.') }}</td>
                <td>Anticipos</td>
                <td class="money">$ {{ number_format((float)($manifiesto['anticipo'] ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Rete Fte.</td>
                <td class="money">$ {{ number_format((float)($manifiesto['reteFuente'] ?? 0), 0, ',', '.') }}</td>
                <td>ICA</td>
                <td class="money">$ {{ number_format((float)($manifiesto['reteIca'] ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Fopat</td>
                <td class="money">$ {{ number_format((float)($manifiesto['fopat'] ?? 0), 0, ',', '.') }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
 
    @php
        // Cálculo del saldo descontando anticipo, reteFuente, reteIca y fopat
        $fleteNeto = (float)($manifiesto['fleteNeto'] ?? 0);
        $anticipo = (float)($manifiesto['anticipo'] ?? 0);
        $reteFuente = (float)($manifiesto['reteFuente'] ?? 0);
        $reteIca = (float)($manifiesto['reteIca'] ?? 0);
        $fopat = (float)($manifiesto['fopat'] ?? 0); // ✅ ya viene calculado del controlador (fleteNeto * 0.001)
        $saldo = $fleteNeto - $anticipo;
        $saldoPagarCalculado = $fleteNeto - $anticipo - $reteFuente - $reteIca - $fopat;
    @endphp
 
    <table class="totals-table" style="margin-top: 12px;">
        <tr>
            <td width="33%">
                <span class="total-label">FLETE NETO</span>
                <span class="total-value">$ {{ number_format($fleteNeto, 0, ',', '.') }}</span>
            </td>
            <td width="33%" class="bg-saldo">
                <span class="total-label">SALDO</span>
                <span class="total-value">$ {{ number_format($saldo, 0, ',', '.') }}</span>
            </td>
            <td width="33%" class="bg-final">
                <span class="total-label">TOTAL LIQUIDACIÓN</span>
                <span class="total-value">$ {{ number_format($saldoPagarCalculado, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>
 
    <div style="margin-top: 20px; border-top: 1px solid #CBD7DE; padding-top: 8px; font-size: 8px; color: #70777D; text-align: center;">
        PLEXA S.A.S &nbsp;•&nbsp; Generado el {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>