import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../data/models/prestasi.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/providers/prestasi_provider.dart';

class PrestasiDetailScreen extends StatelessWidget {
  const PrestasiDetailScreen({super.key, required this.prestasi});

  final Prestasi prestasi;

  @override
  Widget build(BuildContext context) {
    final isAdmin = context.watch<AuthProvider>().user?.isAdmin ?? false;
    final pending = prestasi.statusVerifikasi == 'Pending';

    return Scaffold(
      appBar: GradientAppBar(title: 'Detail Prestasi'),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Container(
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
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Icon(Icons.emoji_events_rounded,
                    color: Colors.white, size: 36),
                const SizedBox(height: 12),
                Text(
                  prestasi.namaLomba ?? '-',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  prestasi.mahasiswa?.nama ?? '-',
                  style: TextStyle(
                    color: Colors.white.withValues(alpha: 0.85),
                    fontSize: 14,
                  ),
                ),
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 12,
                    vertical: 6,
                  ),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.22),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    prestasi.statusVerifikasi ?? '-',
                    style: const TextStyle(
                      color: Colors.white,
                      fontWeight: FontWeight.w700,
                      fontSize: 12,
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  _Row(label: 'Jenis', value: prestasi.jenis?.namaJenis ?? '-'),
                  _Row(
                    label: 'Tingkat',
                    value: prestasi.tingkat?.namaTingkat ?? '-',
                  ),
                  _Row(label: 'Penyelenggara', value: prestasi.penyelenggara ?? '-'),
                  _Row(label: 'Tanggal', value: _formatDate(prestasi.tanggal)),
                  _Row(label: 'Juara', value: prestasi.juara ?? '-'),
                  if (prestasi.sertifikat != null &&
                      prestasi.sertifikat!.isNotEmpty)
                    _Row(label: 'Sertifikat', value: prestasi.sertifikat!),
                ],
              ),
            ),
          ),
          if (isAdmin && pending) ...[
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => _verifikasi(context, 'Ditolak'),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: Colors.red.shade400,
                      side: BorderSide(color: Colors.red.shade400),
                      minimumSize: const Size.fromHeight(48),
                    ),
                    icon: const Icon(Icons.close_rounded),
                    label: const Text('Tolak'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: FilledButton.icon(
                    onPressed: () => _verifikasi(context, 'Disetujui'),
                    style: FilledButton.styleFrom(
                      backgroundColor: Colors.green.shade600,
                      minimumSize: const Size.fromHeight(48),
                    ),
                    icon: const Icon(Icons.check_rounded),
                    label: const Text('Setujui'),
                  ),
                ),
              ],
            ),
          ],
          const SizedBox(height: 16),
        ],
      ),
    );
  }

  Future<void> _verifikasi(BuildContext context, String status) async {
    final catatanController = TextEditingController();
    final result = await showDialog<String>(
      context: context,
      builder: (_) => AlertDialog(
        title: Text(
          status == 'Disetujui' ? 'Setujui Prestasi' : 'Tolak Prestasi',
        ),
        content: TextField(
          controller: catatanController,
          maxLines: 3,
          decoration: const InputDecoration(labelText: 'Catatan (opsional)'),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          FilledButton(
            onPressed: () => Navigator.pop(context, catatanController.text),
            child: const Text('Konfirmasi'),
          ),
        ],
      ),
    );

    if (result != null && context.mounted) {
      try {
        await context
            .read<PrestasiProvider>()
            .verifikasi(id: prestasi.id, status: status, catatan: result);
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Prestasi berhasil diverifikasi')),
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
            width: 130,
            child: Text(
              label,
              style: TextStyle(color: Colors.grey.shade600, fontSize: 13),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13.5),
            ),
          ),
        ],
      ),
    );
  }
}
