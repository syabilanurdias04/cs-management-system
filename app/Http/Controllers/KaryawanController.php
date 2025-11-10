<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Karyawan::query();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        $karyawan = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:karyawan,nik|max:50',
            'nama_lengkap' => 'required|max:255',
            'tempat_lahir' => 'nullable|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,cuti,resign,nonaktif',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after:tanggal_masuk',
            'posisi' => 'nullable|max:100',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/karyawan', $filename);
            $validated['foto'] = $filename;
        }

        Karyawan::create($validated);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['penempatan.lokasi', 'jadwalKerja', 'laporanKegiatan']);

        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nik' => 'required|max:50|unique:karyawan,nik,' . $karyawan->id,
            'nama_lengkap' => 'required|max:255',
            'tempat_lahir' => 'nullable|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,cuti,resign,nonaktif',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after:tanggal_masuk',
            'posisi' => 'nullable|max:100',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($karyawan->foto) {
                Storage::delete('public/karyawan/' . $karyawan->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/karyawan', $filename);
            $validated['foto'] = $filename;
        }

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        // Hapus foto jika ada
        if ($karyawan->foto) {
            Storage::delete('public/karyawan/' . $karyawan->foto);
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }

    /**
     * Export karyawan ke CSV
     */
    public function export()
    {
        $rows = Karyawan::orderBy('created_at', 'desc')
            ->get([
                'id',
                'nik',
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'alamat',
                'no_telepon',
                'email',
                'status',
                'tanggal_masuk',
                'tanggal_keluar',
                'posisi',
            ]);

        $filename = 'karyawan_' . now()->format('Ymd_His') . '.csv';

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'ID', 'NIK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin',
                'Alamat', 'No Telepon', 'Email', 'Status', 'Tanggal Masuk', 'Tanggal Keluar', 'Posisi'
            ]);

            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r->id,
                    $r->nik,
                    $r->nama_lengkap,
                    $r->tempat_lahir,
                    $r->tanggal_lahir,
                    $r->jenis_kelamin,
                    $r->alamat,
                    $r->no_telepon,
                    $r->email,
                    $r->status,
                    $r->tanggal_masuk,
                    $r->tanggal_keluar,
                    $r->posisi,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }

    /**
     * Print kartu karyawan
     */
    public function printKartu(Karyawan $karyawan)
    {
        $pdf = \PDF::loadView('karyawan.kartu', compact('karyawan'));
        return $pdf->download('kartu_karyawan_' . $karyawan->nik . '.pdf');
    }
}