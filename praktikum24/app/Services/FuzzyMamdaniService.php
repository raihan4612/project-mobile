<?php

namespace App\Services;

class FuzzyMamdaniService
{
    // =========================================================================
    //  FUZZIFIKASI IPK
    //  Semesta: 2.00 – 4.00
    //  Himpunan: Rendah (Linear Turun), Sedang (Segitiga), Tinggi (Linear Naik)
    // =========================================================================
    public function fuzzifikasiIPK($ipk): array
    {
        $ipk = max(2.00, min(4.00, (float) $ipk));

        // Rendah — Linear Turun
        // μ=1 jika x ≤ 2.50
        // μ=(3.25-x)/0.75 jika 2.50 < x < 3.25
        // μ=0 jika x ≥ 3.25
        if ($ipk <= 2.50) {
            $rendah = 1.0;
        } elseif ($ipk >= 3.25) {
            $rendah = 0.0;
        } else {
            $rendah = (3.25 - $ipk) / 0.75;
        }

        // Sedang — Segitiga
        // Domain: 2.75 – 3.10 – 3.50
        // μ=0 jika x ≤ 2.75 atau x ≥ 3.50
        // μ=(x-2.75)/0.35 jika 2.75 < x < 3.10
        // μ=(3.50-x)/0.40 jika 3.10 ≤ x < 3.50
        if ($ipk <= 2.75 || $ipk >= 3.50) {
            $sedang = 0.0;
        } elseif ($ipk < 3.10) {
            $sedang = ($ipk - 2.75) / 0.35;
        } else {
            $sedang = (3.50 - $ipk) / 0.40;
        }

        // Tinggi — Linear Naik
        // μ=0 jika x ≤ 3.25
        // μ=(x-3.25)/0.75 jika 3.25 < x < 4.00
        // μ=1 jika x ≥ 4.00
        if ($ipk <= 3.25) {
            $tinggi = 0.0;
        } elseif ($ipk >= 4.00) {
            $tinggi = 1.0;
        } else {
            $tinggi = ($ipk - 3.25) / 0.75;
        }

        return [
            'Rendah' => round($rendah, 4),
            'Sedang' => round($sedang, 4),
            'Tinggi' => round($tinggi, 4),
        ];
    }

    // =========================================================================
    //  FUZZIFIKASI TINGKAT PRESTASI
    //  Input: skor (0–100) hasil konversi dari tingkat tertinggi
    //  Kampus=20, Kota=40, Provinsi=60, Nasional=80, Internasional=100
    //  Himpunan: Rendah (Linear Turun), Sedang (Segitiga), Tinggi (Linear Naik)
    // =========================================================================
    public function fuzzifikasiPrestasi($skor): array
    {
        $skor = max(0, min(100, (float) $skor));

        // Rendah — Linear Turun
        // Domain: 0–50
        // μ=1 jika x ≤ 20
        // μ=(50-x)/30 jika 20 < x < 50
        // μ=0 jika x ≥ 50
        if ($skor <= 20) {
            $rendah = 1.0;
        } elseif ($skor >= 50) {
            $rendah = 0.0;
        } else {
            $rendah = (50 - $skor) / 30;
        }

        // Sedang — Segitiga
        // Domain: 30 – 50 – 70
        // μ=0 jika x ≤ 30 atau x ≥ 70
        // μ=(x-30)/20 jika 30 < x < 50
        // μ=(70-x)/20 jika 50 ≤ x < 70
        if ($skor <= 30 || $skor >= 70) {
            $sedang = 0.0;
        } elseif ($skor < 50) {
            $sedang = ($skor - 30) / 20;
        } else {
            $sedang = (70 - $skor) / 20;
        }

        // Tinggi — Linear Naik
        // Domain: 50–100
        // μ=0 jika x ≤ 50
        // μ=(x-50)/50 jika 50 < x < 100
        // μ=1 jika x ≥ 100
        if ($skor <= 50) {
            $tinggi = 0.0;
        } elseif ($skor >= 100) {
            $tinggi = 1.0;
        } else {
            $tinggi = ($skor - 50) / 50;
        }

        return [
            'Rendah' => round($rendah, 4),
            'Sedang' => round($sedang, 4),
            'Tinggi' => round($tinggi, 4),
        ];
    }

    // =========================================================================
    //  FUZZIFIKASI JUMLAH PRESTASI
    //  Semesta: 0 – 10
    //  Himpunan: Sedikit (Linear Turun), Sedang (Segitiga), Banyak (Linear Naik)
    // =========================================================================
    public function fuzzifikasiJumlahPrestasi($jumlah): array
    {
        $jumlah = max(0, min(10, (float) $jumlah));

        // Sedikit — Linear Turun
        // Domain: 0–4
        // μ=1 jika x ≤ 1
        // μ=(4-x)/3 jika 1 < x < 4
        // μ=0 jika x ≥ 4
        if ($jumlah <= 1) {
            $sedikit = 1.0;
        } elseif ($jumlah >= 4) {
            $sedikit = 0.0;
        } else {
            $sedikit = (4 - $jumlah) / 3;
        }

        // Sedang — Segitiga
        // Domain: 1 – 5 – 8
        // μ=0 jika x ≤ 1 atau x ≥ 8
        // μ=(x-1)/4 jika 1 < x < 5
        // μ=(8-x)/3 jika 5 ≤ x < 8
        if ($jumlah <= 1 || $jumlah >= 8) {
            $sedang = 0.0;
        } elseif ($jumlah < 5) {
            $sedang = ($jumlah - 1) / 4;
        } else {
            $sedang = (8 - $jumlah) / 3;
        }

        // Banyak — Linear Naik
        // Domain: 6–10
        // μ=0 jika x ≤ 6
        // μ=(x-6)/4 jika 6 < x < 10
        // μ=1 jika x ≥ 10
        if ($jumlah <= 6) {
            $banyak = 0.0;
        } elseif ($jumlah >= 10) {
            $banyak = 1.0;
        } else {
            $banyak = ($jumlah - 6) / 4;
        }

        return [
            'Sedikit' => round($sedikit, 4),
            'Sedang'  => round($sedang, 4),
            'Banyak'  => round($banyak, 4),
        ];
    }

    // =========================================================================
    //  OUTPUT MEMBERSHIP FUNCTIONS (digunakan saat agregasi & centroid)
    //  Semesta: 0 – 100
    //  Himpunan: TidakLayak, Dipertimbangkan, Layak, SangatLayak
    // =========================================================================

    // μ Tidak Layak — Linear Turun (domain 0–20–40)
    private function muTidakLayak($x): float
    {
        if ($x <= 20) return 1.0;
        if ($x >= 40) return 0.0;
        return (40 - $x) / 20;
    }

    // μ Dipertimbangkan — Segitiga (domain 30–45–60)
    private function muDipertimbangkan($x): float
    {
        if ($x <= 30 || $x >= 60) return 0.0;
        if ($x < 45) return ($x - 30) / 15;
        return (60 - $x) / 15;
    }

    // μ Layak — Segitiga (domain 55–70–85)
    private function muLayak($x): float
    {
        if ($x <= 55 || $x >= 85) return 0.0;
        if ($x < 70) return ($x - 55) / 15;
        return (85 - $x) / 15;
    }

    // μ Sangat Layak — Linear Naik (domain 70–100)
    private function muSangatLayak($x): float
    {
        if ($x <= 70) return 0.0;
        if ($x >= 100) return 1.0;
        return ($x - 70) / 30;
    }

    // =========================================================================
    //  INFERENSI (MAMDANI)
    //  10 rule base, operator AND = MIN
    //  Output: array [TidakLayak, Dipertimbangkan, Layak, SangatLayak]
    //  Masing-masing berisi nilai α (derajat keanggotaan hasil inferensi)
    // =========================================================================
    public function inferensi(array $fIPK, array $fPres, array $fJml): array
    {
        // ─── SANGAT LAYAK ───────────────────────────────────────────────
        // R1 : IPK=Tinggi AND Prestasi=Tinggi AND Jml=Banyak → SangatLayak
        $r1 = min($fIPK['Tinggi'], $fPres['Tinggi'], $fJml['Banyak']);

        // R2 : IPK=Tinggi AND Prestasi=Tinggi AND Jml=Sedang → SangatLayak
        $r2 = min($fIPK['Tinggi'], $fPres['Tinggi'], $fJml['Sedang']);

        // R3 : IPK=Sedang AND Prestasi=Tinggi AND Jml=Banyak → SangatLayak
        $r3 = min($fIPK['Sedang'], $fPres['Tinggi'], $fJml['Banyak']);

        // ─── LAYAK ──────────────────────────────────────────────────────
        // R4 : IPK=Tinggi AND Prestasi=Tinggi AND Jml=Sedikit → Layak
        $r4 = min($fIPK['Tinggi'], $fPres['Tinggi'], $fJml['Sedikit']);

        // R5 : IPK=Tinggi AND Prestasi=Sedang AND Jml=Banyak → Layak
        $r5 = min($fIPK['Tinggi'], $fPres['Sedang'], $fJml['Banyak']);

        // R6 : IPK=Tinggi AND Prestasi=Sedang AND Jml=Sedang → Layak
        $r6 = min($fIPK['Tinggi'], $fPres['Sedang'], $fJml['Sedang']);

        // R7 : IPK=Sedang AND Prestasi=Tinggi AND Jml=Sedang → Layak
        $r7 = min($fIPK['Sedang'], $fPres['Tinggi'], $fJml['Sedang']);

        // R8 : IPK=Sedang AND Prestasi=Sedang AND Jml=Banyak → Layak
        $r8 = min($fIPK['Sedang'], $fPres['Sedang'], $fJml['Banyak']);

        // R9 : IPK=Tinggi AND Prestasi=Rendah AND Jml=Banyak → Layak
        $r9 = min($fIPK['Tinggi'], $fPres['Rendah'], $fJml['Banyak']);

        // ─── DIPERTIMBANGKAN ────────────────────────────────────────────
        // R10: IPK=Tinggi AND Prestasi=Sedang AND Jml=Sedikit → Dipertimbangkan
        $r10 = min($fIPK['Tinggi'], $fPres['Sedang'], $fJml['Sedikit']);

        // R11: IPK=Tinggi AND Prestasi=Rendah AND Jml=Sedang → Dipertimbangkan
        $r11 = min($fIPK['Tinggi'], $fPres['Rendah'], $fJml['Sedang']);

        // R12: IPK=Tinggi AND Prestasi=Rendah AND Jml=Sedikit → Dipertimbangkan
        $r12 = min($fIPK['Tinggi'], $fPres['Rendah'], $fJml['Sedikit']);

        // R13: IPK=Sedang AND Prestasi=Tinggi AND Jml=Sedikit → Dipertimbangkan
        $r13 = min($fIPK['Sedang'], $fPres['Tinggi'], $fJml['Sedikit']);

        // R14: IPK=Sedang AND Prestasi=Sedang AND Jml=Sedang → Dipertimbangkan
        $r14 = min($fIPK['Sedang'], $fPres['Sedang'], $fJml['Sedang']);

        // R15: IPK=Sedang AND Prestasi=Sedang AND Jml=Sedikit → Dipertimbangkan
        $r15 = min($fIPK['Sedang'], $fPres['Sedang'], $fJml['Sedikit']);

        // R16: IPK=Sedang AND Prestasi=Rendah AND Jml=Banyak → Dipertimbangkan
        $r16 = min($fIPK['Sedang'], $fPres['Rendah'], $fJml['Banyak']);

        // R17: IPK=Sedang AND Prestasi=Rendah AND Jml=Sedang → Dipertimbangkan
        $r17 = min($fIPK['Sedang'], $fPres['Rendah'], $fJml['Sedang']);

        // R18: IPK=Rendah AND Prestasi=Tinggi AND Jml=Banyak → Dipertimbangkan
        $r18 = min($fIPK['Rendah'], $fPres['Tinggi'], $fJml['Banyak']);

        // R19: IPK=Rendah AND Prestasi=Tinggi AND Jml=Sedang → Dipertimbangkan
        $r19 = min($fIPK['Rendah'], $fPres['Tinggi'], $fJml['Sedang']);

        // R20: IPK=Rendah AND Prestasi=Tinggi AND Jml=Sedikit → Dipertimbangkan
        $r20 = min($fIPK['Rendah'], $fPres['Tinggi'], $fJml['Sedikit']);

        // R21: IPK=Rendah AND Prestasi=Sedang AND Jml=Banyak → Dipertimbangkan
        $r21 = min($fIPK['Rendah'], $fPres['Sedang'], $fJml['Banyak']);

        // R22: IPK=Rendah AND Prestasi=Sedang AND Jml=Sedang → Dipertimbangkan
        $r22 = min($fIPK['Rendah'], $fPres['Sedang'], $fJml['Sedang']);

        // ─── TIDAK LAYAK ────────────────────────────────────────────────
        // R23: IPK=Sedang AND Prestasi=Rendah AND Jml=Sedikit → TidakLayak
        $r23 = min($fIPK['Sedang'], $fPres['Rendah'], $fJml['Sedikit']);

        // R24: IPK=Rendah AND Prestasi=Sedang AND Jml=Sedikit → TidakLayak
        $r24 = min($fIPK['Rendah'], $fPres['Sedang'], $fJml['Sedikit']);

        // R25: IPK=Rendah AND Prestasi=Rendah AND Jml=Banyak → TidakLayak
        $r25 = min($fIPK['Rendah'], $fPres['Rendah'], $fJml['Banyak']);

        // R26: IPK=Rendah AND Prestasi=Rendah AND Jml=Sedang → TidakLayak
        $r26 = min($fIPK['Rendah'], $fPres['Rendah'], $fJml['Sedang']);

        // R27: IPK=Rendah AND Prestasi=Rendah AND Jml=Sedikit → TidakLayak
        $r27 = min($fIPK['Rendah'], $fPres['Rendah'], $fJml['Sedikit']);

        return [
            'TidakLayak'       => max($r23, $r24, $r25, $r26, $r27),
            'Dipertimbangkan'  => max($r10, $r11, $r12, $r13, $r14, $r15, $r16, $r17, $r18, $r19, $r20, $r21, $r22),
            'Layak'            => max($r4, $r5, $r6, $r7, $r8, $r9),
            'SangatLayak'      => max($r1, $r2, $r3),
        ];
    }

    // =========================================================================
    //  AGREGASI (MAX)
    //  Menggabungkan hasil inferensi per output kategori
    //  Menghasilkan fungsi keanggotaan agregat μ(x) untuk setiap x
    // =========================================================================
    public function agregasi(array $inferensi): array
    {
        // Sampling di 201 titik (0 – 100, step 0.5)
        $points = [];
        for ($x = 0; $x <= 100; $x += 0.5) {
            $x = round($x, 1);

            // Ambil μ dari masing-masing output himpunan
            $muTL = $this->muTidakLayak($x);
            $muDP = $this->muDipertimbangkan($x);
            $muLY = $this->muLayak($x);
            $muSL = $this->muSangatLayak($x);

            // MIN antara α-cut inferensi dengan μ himpunan (Mamdani: clipping)
            $clipTL = min($inferensi['TidakLayak'], $muTL);
            $clipDP = min($inferensi['Dipertimbangkan'], $muDP);
            $clipLY = min($inferensi['Layak'], $muLY);
            $clipSL = min($inferensi['SangatLayak'], $muSL);

            // Agregasi: MAX dari semua clipping
            $points["$x"] = max($clipTL, $clipDP, $clipLY, $clipSL);
        }

        return $points;
    }

    // =========================================================================
    //  DEFUZZIFIKASI — CENTROID
    //  Σ(x * μ(x)) / Σ(μ(x))
    // =========================================================================
    public function centroid(array $agregasi): float
    {
        $sumMu  = 0.0;
        $sumXMu = 0.0;

        foreach ($agregasi as $xStr => $mu) {
            $x = (float) $xStr;
            $sumMu  += $mu;
            $sumXMu += $x * $mu;
        }

        if ($sumMu == 0) {
            return 0.0;
        }

        return round($sumXMu / $sumMu, 2);
    }

    // =========================================================================
    //  MENENTUKAN KATEGORI HASIL
    // =========================================================================
    public function tentukanKategori($nilaiFuzzy): string
    {
        if ($nilaiFuzzy < 40) return 'Tidak Layak';
        if ($nilaiFuzzy < 60) return 'Dipertimbangkan';
        if ($nilaiFuzzy < 80) return 'Layak';
        return 'Sangat Layak';
    }

    // =========================================================================
    //  ORKESTRATOR — Memanggil seluruh proses secara berurutan
    //
    //  Input:
    //    $ipk             — nilai IPK mahasiswa
    //    $tingkatSkor     — skor tingkat prestasi tertinggi (0–100)
    //    $jumlahPrestasi  — jumlah prestasi yang dimiliki (0–10)
    //
    //  Output:
    //    ['nilai_fuzzy' => float, 'hasil_rekomendasi' => string]
    // =========================================================================
    public function hitung($ipk, $tingkatSkor, $jumlahPrestasi): array
    {
        // 1. Fuzzifikasi
        $fIPK = $this->fuzzifikasiIPK($ipk);
        $fPres = $this->fuzzifikasiPrestasi($tingkatSkor);
        $fJml = $this->fuzzifikasiJumlahPrestasi($jumlahPrestasi);

        // 2. Inferensi (rule base Mamdani)
        $inferensi = $this->inferensi($fIPK, $fPres, $fJml);

        // 3. Agregasi (MAX + clipping)
        $agregasi = $this->agregasi($inferensi);

        // 4. Defuzzifikasi (Centroid)
        $nilaiFuzzy = $this->centroid($agregasi);

        // 5. Tentukan kategori
        $kategori = $this->tentukanKategori($nilaiFuzzy);

        return [
            'nilai_fuzzy'        => $nilaiFuzzy,
            'hasil_rekomendasi'  => $kategori,
        ];
    }
}
