<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ad Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3B82F6; padding-bottom: 20px; }
        .header h1 { font-size: 24px; color: #1f2937; margin-bottom: 5px; }
        .header p { color: #6b7280; }
        
        .summary { width: 100%; margin-bottom: 30px; display: table; table-layout: fixed; }
        .summary-card { display: table-cell; background: #f9fafb; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #e5e7eb; }
        .summary-card h3 { font-size: 10px; color: #6b7280; text-transform: uppercase; margin-bottom: 5px; }
        .summary-card p { font-size: 16px; font-weight: bold; color: #1f2937; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #3B82F6; color: white; padding: 12px 8px; text-align: left; font-weight: 600; }
        td { padding: 10px 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #f9fafb; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 30px; text-align: center; color: #9ca3af; font-size: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ad Performance Report</h1>
            <p>Generated on {{ now()->format('F j, Y, g:i A') }}</p>
        </div>
        
        <div class="summary">
            <div class="summary-card">
                <h3>Total Spend</h3>
<p>Rp {{ number_format($totalSpend, 0, ',', '.') }}</p>
            </div>
            <div class="summary-card">
                <h3>Impressions</h3>
                <p>{{ number_format($totalImpressions) }}</p>
            </div>
            <div class="summary-card">
                <h3>Clicks</h3>
                <p>{{ number_format($totalClicks) }}</p>
            </div>
            <div class="summary-card">
                <h3>Conversions</h3>
                <p>{{ number_format($totalConversions) }}</p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Platform</th>
                    <th>Campaign</th>
                    <th class="text-right">Impressions</th>
                    <th class="text-right">Clicks</th>
                    <th class="text-right">Conversions</th>
                    <th class="text-right">Spend</th>
                    <th class="text-right">CTR</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td>{{ $report['record_date'] }}</td>
                    <td style="text-transform: capitalize;">{{ $report['platform'] }}</td>
                    <td>{{ $report['campaign'] }}</td>
                    <td class="text-right">{{ number_format($report['impressions']) }}</td>
                    <td class="text-right">{{ number_format($report['clicks']) }}</td>
                    <td class="text-right">{{ number_format($report['conversions']) }}</td>
                    <td class="text-right">Rp {{ number_format($report['spend'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ $report['impressions'] > 0 ? number_format(($report['clicks'] / $report['impressions']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <p>Ad Dashboard - Multi-Platform Advertising Report</p>
        </div>
    </div>
</body>
</html>
