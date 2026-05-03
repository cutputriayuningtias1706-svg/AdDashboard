<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class VendorController extends Controller
{
    // Mocked Vendors Data
    private function getVendors()
    {
        // For realistic simulation, we might store status in session if changed
        $sessionVendors = session('vendors_status', []);

        $baseVendors = [
            'pluto' => [
                'id' => 'pluto',
                'name' => 'Pluto Networks',
                'logo' => 'fa-network-wired',
                'color' => 'text-blue-500',
                'bg_color' => 'bg-blue-50',
                'border_color' => 'border-blue-200',
                'description' => 'Jaringan periklanan global dengan inventaris premium dan targeting audiens yang mendalam.',
                'status' => 'inactive',
                'pks_available' => true,
            ],
            'sabupp' => [
                'id' => 'sabupp',
                'name' => 'Sabupp',
                'logo' => 'fa-bolt',
                'color' => 'text-amber-500',
                'bg_color' => 'bg-amber-50',
                'border_color' => 'border-amber-200',
                'description' => 'Platform DSP terkemuka untuk optimasi performa iklan real-time di Asia Tenggara.',
                'status' => 'inactive',
                'pks_available' => true,
            ],
            'yingliang' => [
                'id' => 'yingliang',
                'name' => 'Yingliang',
                'logo' => 'fa-dragon',
                'color' => 'text-red-500',
                'bg_color' => 'bg-red-50',
                'border_color' => 'border-red-200',
                'description' => 'Publisher eksklusif dengan akses langsung ke lalu lintas pengguna high-intent di pasar Asia.',
                'status' => 'inactive',
                'pks_available' => true,
            ],
            'fingerads' => [
                'id' => 'fingerads',
                'name' => 'Fingerads',
                'logo' => 'fa-hand-pointer',
                'color' => 'text-purple-500',
                'bg_color' => 'bg-purple-50',
                'border_color' => 'border-purple-200',
                'description' => 'Spesialis mobile advertising network untuk kampanye instalasi aplikasi dan akuisisi user.',
                'status' => 'inactive',
                'pks_available' => true,
            ],
        ];

        // Override status from session if applied
        foreach ($sessionVendors as $id => $status) {
            if (isset($baseVendors[$id])) {
                $baseVendors[$id]['status'] = $status;
            }
        }

        return $baseVendors;
    }

    public function index()
    {
        $vendors = collect($this->getVendors());
        return view('contracts.index', compact('vendors'));
    }

    public function apply($id)
    {
        $vendors = $this->getVendors();
        if (!isset($vendors[$id])) {
            abort(404);
        }

        $vendor = (object) $vendors[$id];
        return view('contracts.apply', compact('vendor'));
    }

    public function store(Request $request, $id)
    {
        $vendors = $this->getVendors();
        if (!isset($vendors[$id])) {
            abort(404);
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email',
            'npwp' => 'required|string',
            'api_token' => 'required|string',
            'legality_file' => 'required|file|mimes:pdf,zip|max:10240',
            'terms_accepted' => 'accepted'
        ]);

        // Simulating API integration request and status update
        $sessionVendors = session('vendors_status', []);
        $sessionVendors[$id] = 'pending'; // Change status to pending review or active
        session(['vendors_status' => $sessionVendors]);

        return redirect()->route('contracts.index')->with('success', 'Pengajuan kontrak kerjasama dengan ' . $vendors[$id]['name'] . ' berhasil dikirim. Menunggu verifikasi API Token dan Legalitas dari pihak vendor.');
    }

    public function downloadPks($id)
    {
        $vendors = $this->getVendors();
        if (!isset($vendors[$id])) {
            abort(404);
        }

        $vendorName = $vendors[$id]['name'];

        // Simulating a PKS download
        $html = "
            <div style='font-family: sans-serif; padding: 40px;'>
                <h1 style='text-align:center;'>PERJANJIAN KERJA SAMA (PKS)</h1>
                <h2 style='text-align:center; color: #4f46e5;'>Platform Integrasi Iklan</h2>
                <hr style='margin: 30px 0;'>
                <p><strong>PIHAK PERTAMA:</strong> PT Indosaku Digital Teknologi (AdDashboard Pro)</p>
                <p><strong>PIHAK KEDUA:</strong> {$vendorName}</p>
                <br>
                <h3>1. RUANG LINGKUP</h3>
                <p>Pihak Kedua memberikan akses API penuh kepada Pihak Pertama untuk mendistribusikan, melacak, dan mengelola kampanye iklan digital.</p>
                <h3>2. INTEGRASI SISTEM</h3>
                <p>Integrasi dilakukan melalui Access Token yang sah dengan standar keamanan yang telah disepakati.</p>
                <h3>3. PEMBAGIAN PENDAPATAN (REVENUE SHARE)</h3>
                <p>Skema revenue share akan dihitung berdasarkan total disbursement bulanan sesuai lampiran B.</p>
                <br><br><br>
                <table width='100%'>
                    <tr>
                        <td width='50%' align='center'>PIHAK PERTAMA<br><br><br><br>____________________</td>
                        <td width='50%' align='center'>PIHAK KEDUA<br><br><br><br>____________________</td>
                    </tr>
                </table>
            </div>
        ";

        try {
            $pdf = \PDF::loadHTML($html);
            return $pdf->download("PKS_Kerjasama_AdDashboard_x_{$vendorName}.pdf");
        } catch (\Exception $e) {
            // Fallback if PDF package is not installed/working correctly
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', "attachment; filename=PKS_Kerjasama_AdDashboard_x_{$vendorName}.html");
        }
    }
}
