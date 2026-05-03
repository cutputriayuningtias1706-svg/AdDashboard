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
                'pks_url' => 'https://drive.google.com/uc?export=download&id=1B-A2Yq5V2ptGux0Y5x0iNRXxYZfvQVbu',
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
                'pks_url' => 'https://drive.google.com/uc?export=download&id=1az8-bD3tvByrxacRBfx8-XxuVPXXHfI3',
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
                'pks_url' => 'https://drive.google.com/uc?export=download&id=1VRxH5UyRZZMOAylWHMfzOvbJe47K6EL5',
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
                'pks_url' => 'https://drive.google.com/uc?export=download&id=1IUjNtu3QvMyZKxITBuWwfHtTrZJ6QRMe',
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
        if (!isset($vendors[$id]) || !isset($vendors[$id]['pks_url'])) {
            abort(404);
        }

        return redirect()->away($vendors[$id]['pks_url']);
    }
}
