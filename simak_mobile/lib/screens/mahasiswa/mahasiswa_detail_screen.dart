import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/providers/mahasiswa_provider.dart';
import 'mahasiswa_form_screen.dart';

class MahasiswaDetailScreen extends StatelessWidget {
  const MahasiswaDetailScreen({super.key, required this.mahasiswa});

  final Mahasiswa mahasiswa;

  @override
  Widget build(BuildContext context) {
    final isAdmin = context.watch<AuthProvider>().user?.isAdmin ?? false;

    return Scaffold(
      appBar: GradientAppBar(
        title: 'Detail Mahasiswa',
        actions: [
          IconButton(
            tooltip: 'Edit',
            icon: const Icon(Icons.edit_rounded),
            onPressed: () => _openEdit(context),
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          _Header(mahasiswa: mahasiswa),
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  _Row(label: 'NIM', value: mahasiswa.nim),
                  _Row(label: 'Email', value: mahasiswa.email ?? '-'),
                  _Row(label: 'No. HP', value: mahasiswa.noHp ?? '-'),
                  _Row(label: 'Jenis Kelamin', value: mahasiswa.jenisKelamin == 'L' ? 'Laki-laki' : mahasiswa.jenisKelamin == 'P' ? 'Perempuan' : '-'),
                  _Row(label: 'Tempat, Tanggal Lahir', value: '${mahasiswa.tempatLahir ?? '-'}, ${_formatDate(mahasiswa.tanggalLahir)}'),
                  _Row(label: 'Alamat', value: _fullAddress),
                  _Row(label: 'Prodi', value: mahasiswa.prodi ?? '-'),
                  _Row(label: 'Fakultas', value: mahasiswa.fakultas ?? '-'),
                  _Row(label: 'Semester', value: mahasiswa.semester?.toString() ?? '-'),
                  _Row(label: 'Tahun Masuk', value: mahasiswa.tahunMasuk ?? '-'),
                  _Row(label: 'IPK', value: mahasiswa.ipk?.toString() ?? '-'),
                  _Row(label: 'Peminjaman', value: '${mahasiswa.peminjamanCount} kali'),
                  _Row(label: 'Prestasi', value: '${mahasiswa.prestasiCount} kali'),
                ],
              ),
            ),
          ),
          if (isAdmin) ...[
            const SizedBox(height: 16),
            OutlinedButton.icon(
              onPressed: () => _confirmDelete(context),
              style: OutlinedButton.styleFrom(
                foregroundColor: Colors.red.shade400,
                side: BorderSide(color: Colors.red.shade400),
                minimumSize: const Size.fromHeight(48),
              ),
              icon: const Icon(Icons.delete_outline_rounded),
              label: const Text('Hapus Mahasiswa'),
            ),
          ],
          const SizedBox(height: 16),
        ],
      ),
    );
  }

  String get _fullAddress {
    final parts = [
      mahasiswa.alamat,
      mahasiswa.kota,
      mahasiswa.provinsi,
      mahasiswa.kodePos,
    ].whereType<String>().where((e) => e.isNotEmpty).join(', ');
    return parts.isEmpty ? '-' : parts;
  }

  Future<void> _openEdit(BuildContext context) async {
    final updated = await Navigator.of(context).push<bool>(
      MaterialPageRoute(
        builder: (_) => MahasiswaFormScreen(mahasiswa: mahasiswa),
      ),
    );
    if (updated == true && context.mounted) {
      context.read<MahasiswaProvider>().load(showLoader: false);
      if (context.mounted) Navigator.of(context).pop();
    }
  }

  Future<void> _confirmDelete(BuildContext context) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Hapus Mahasiswa'),
        content: Text(
          'Hapus data ${mahasiswa.nama}? Tindakan ini tidak dapat dibatalkan.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          FilledButton(
            style: FilledButton.styleFrom(
              backgroundColor: Colors.red.shade400,
            ),
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );

    if (confirm == true && context.mounted) {
      try {
        await context.read<MahasiswaProvider>().delete(mahasiswa.id);
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Mahasiswa berhasil dihapus')),
          );
          Navigator.of(context).pop();
        }
      } catch (e) {
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('$e')),
          );
        }
      }
    }
  }

  String _formatDate(String? date) {
    if (date == null || date.isEmpty) return '-';
    final d = DateTime.tryParse(date);
    if (d == null) return date;
    const months = [
      '', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
      'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
    ];
    return '${d.day} ${months[d.month]} ${d.year}';
  }
}

class _Header extends StatelessWidget {
  const _Header({required this.mahasiswa});

  final Mahasiswa mahasiswa;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: AppColors.primaryGradient,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: AppColors.gradientEnd.withValues(alpha: 0.3),
            blurRadius: 12,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Row(
        children: [
          CircleAvatar(
            radius: 34,
            backgroundColor: Colors.white.withValues(alpha: 0.25),
            child: Text(
              mahasiswa.nama.isNotEmpty ? mahasiswa.nama[0].toUpperCase() : '?',
              style: const TextStyle(
                color: Colors.white,
                fontSize: 30,
                fontWeight: FontWeight.w800,
              ),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  mahasiswa.nama,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 19,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  mahasiswa.nim,
                  style: TextStyle(
                    color: Colors.white.withValues(alpha: 0.85),
                    fontSize: 14,
                  ),
                ),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.22),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Text(
              mahasiswa.status ?? '-',
              style: const TextStyle(
                color: Colors.white,
                fontWeight: FontWeight.w700,
                fontSize: 12,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _Row extends StatelessWidget {
  const _Row({required this.label, required this.value});

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 150,
            child: Text(
              label,
              style: TextStyle(color: Colors.grey.shade600, fontSize: 13),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontWeight: FontWeight.w600,
                fontSize: 13.5,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
