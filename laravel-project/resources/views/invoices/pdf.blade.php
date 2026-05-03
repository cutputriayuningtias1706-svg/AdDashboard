<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice {{ $invoice->invoice_number }}</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size:11px; color:#1e293b; background:#fff; }
    .page { padding:40px 48px; }

    /* ── Header ── */
    .header { border-bottom:2px solid #6366f1; padding-bottom:20px; margin-bottom:28px; }
    .header-inner { width:100%; }
    .brand { font-size:22px; font-weight:700; color:#6366f1; }
    .brand-sub { font-size:10px; color:#94a3b8; margin-top:2px; }
    .invoice-label { font-size:26px; font-weight:700; color:#1e293b; text-align:right; }
    .invoice-meta { text-align:right; margin-top:6px; font-size:10px; color:#64748b; line-height:1.8; }
    .invoice-meta span { color:#1e293b; font-weight:600; }

    /* ── Status Badge ── */
    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
    .badge-paid    { background:#dcfce7; color:#166534; border:1px solid #86efac; }
    .badge-pending { background:#fef9c3; color:#854d0e; border:1px solid #fde047; }
    .badge-overdue { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
    .badge-draft   { background:#f1f5f9; color:#64748b; border:1px solid #cbd5e1; }

    /* ── Bill To / Bill From ── */
    .parties { margin-bottom:28px; }
    .party-box { width:48%; display:inline-block; vertical-align:top; }
    .party-box.right { float:right; text-align:right; }
    .party-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:#94a3b8; margin-bottom:6px; }
    .party-name { font-size:13px; font-weight:700; color:#1e293b; }
    .party-detail { font-size:10px; color:#64748b; line-height:1.7; margin-top:3px; }

    /* ── Table ── */
    table { width:100%; border-collapse:collapse; margin-bottom:24px; }
    thead tr { background:#f8fafc; }
    th { padding:10px 14px; text-align:left; font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:#64748b; border-bottom:1px solid #e2e8f0; }
    th.right, td.right { text-align:right; }
    td { padding:12px 14px; font-size:11px; color:#334155; border-bottom:1px solid #f1f5f9; }
    tr:last-child td { border-bottom:none; }

    /* ── Totals ── */
    .totals-wrap { text-align:right; margin-bottom:28px; }
    .totals-row { margin-bottom:6px; font-size:11px; color:#64748b; }
    .totals-row span { display:inline-block; width:140px; font-size:11px; }
    .totals-row .val { font-weight:600; color:#1e293b; }
    .totals-total { border-top:2px solid #6366f1; padding-top:10px; margin-top:10px; font-size:14px; font-weight:700; color:#1e293b; }
    .totals-total span { display:inline-block; width:140px; }

    /* ── Notes / Payment Info ── */
    .info-box { background:#f8fafc; border-left:3px solid #6366f1; padding:14px 18px; margin-bottom:24px; border-radius:4px; }
    .info-box p { font-size:10px; color:#64748b; line-height:1.7; }
    .info-box strong { color:#1e293b; }

    /* ── Footer ── */
    .footer { border-top:1px solid #e2e8f0; padding-top:16px; text-align:center; color:#94a3b8; font-size:9px; }
</style>
</head>
<body>
<div class="page">

    <!-- Header -->
    <div class="header">
        <table style="width:100%;"><tr>
            <td style="width:50%;">
                <div class="brand">AdDashboard Pro</div>
                <div class="brand-sub">Multi-Platform Ad Management</div>
            </td>
            <td style="width:50%;text-align:right;">
                <div class="invoice-label">INVOICE</div>
                <div class="invoice-meta">
                    No: <span>{{ $invoice->invoice_number }}</span><br>
                    Tanggal: <span>{{ $invoice->created_at->format('d F Y') }}</span><br>
                    Jatuh Tempo: <span>{{ $invoice->due_date ? $invoice->due_date->format('d F Y') : 'N/A' }}</span><br>
                    Status:
                    @switch($invoice->status)
                        @case('paid')    <span class="badge badge-paid">Lunas</span> @break
                        @case('pending') <span class="badge badge-pending">Menunggu</span> @break
                        @case('overdue') <span class="badge badge-overdue">Jatuh Tempo</span> @break
                        @default         <span class="badge badge-draft">Draft</span>
                    @endswitch
                </div>
            </td>
        </tr></table>
    </div>

    <!-- Bill To / From -->
    <div class="parties">
        <div class="party-box">
            <div class="party-label">Tagih Kepada</div>
            <div class="party-name">{{ $invoice->adAccount->account_name }}</div>
            <div class="party-detail">
                Platform: {{ ucfirst($invoice->adAccount->platform) }} Ads<br>
                Account ID: {{ $invoice->adAccount->account_id }}<br>
                @if($invoice->adAccount->description)
                    {{ $invoice->adAccount->description }}
                @endif
            </div>
        </div>
        <div class="party-box right">
            <div class="party-label">Dari</div>
            <div class="party-name">AdDashboard Pro</div>
            <div class="party-detail">
                Billing Department<br>
                billing@addashboard.id<br>
                Jakarta, Indonesia
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>

    <!-- Line Items Table -->
    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th>Deskripsi Layanan</th>
                <th>Periode</th>
                <th class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <strong>Jasa Periklanan – {{ ucfirst($invoice->adAccount->platform) }} Ads</strong><br>
                    <span style="font-size:10px;color:#94a3b8;">Manajemen kampanye iklan digital</span>
                </td>
                <td style="font-size:10px;color:#64748b;">
                    {{ $invoice->period_start->format('d M Y') }}<br>s/d {{ $invoice->period_end->format('d M Y') }}
                </td>
                <td class="right" style="font-weight:600;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-wrap">
        <div class="totals-row">
            <span>Subtotal</span>
            <span class="val">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
        </div>
        <div class="totals-row">
            <span>PPN (10%)</span>
            <span class="val">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</span>
        </div>
        <div class="totals-row totals-total">
            <span>Total Tagihan</span>
            <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="info-box">
        <p><strong>Informasi Pembayaran</strong></p>
        <p style="margin-top:6px;">
            Harap selesaikan pembayaran dalam 30 hari dari tanggal invoice untuk menghindari gangguan layanan.<br>
            Transfer ke: <strong>Bank BCA – 880-1234-567 – a/n PT Indosaku Digital Teknologi</strong><br>
            Gunakan nomor invoice <strong>{{ $invoice->invoice_number }}</strong> sebagai referensi pembayaran.
        </p>
    </div>

    @if($invoice->notes)
    <div class="info-box" style="border-color:#10b981;">
        <p><strong>Catatan:</strong> {{ $invoice->notes }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda! &nbsp;·&nbsp; AdDashboard Pro – Multi-Platform Advertising Management</p>
        <p style="margin-top:4px;">Dokumen ini dibuat secara otomatis oleh sistem pada {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

</div>
</body>
</html>
