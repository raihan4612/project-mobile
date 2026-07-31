import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/jenis_tingkat_prestasi.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/repositories/lookup_repository.dart';
import '../../data/repositories/mahasiswa_repository.dart';
import '../../data/repositories/prestasi_repository.dart';

class PrestasiFormScreen extends StatefulWidget {
  const PrestasiFormScreen({super.key});

  @override
  State<PrestasiFormScreen> createState() => _PrestasiFormScreenState();
}

class _PrestasiFormScreenState extends State<PrestasiFormScreen> {
  final _repo = PrestasiRepository();

  List<Mahasiswa> _mahasiswaList = [];
  List<JenisPrestasi> _jenisList = [];
  List<TingkatPrestasi> _tingkatList = [];
  bool _loading = true;
  String? _loadError;

  final _namaLomba = TextEditingController();
  final _penyelenggara = TextEditingController();

  int? _mahasiswaId;
  int? _jenisId;
  int? _tingkatId;
  String? _tanggal;
  String? _juara;
  File? _sertifikat;

  bool _saving = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  @override
  void dispose() {
    _namaLomba.dispose();
    _penyelenggara.dispose();
    super.dispose();
  }

  Future<void> _loadData() async {
    setState(() {
      _loading = true;
      _loadError = null;
    });

    try {
      final lookup = LookupRepository();
      final results = await Future.wait([
        MahasiswaRepository().getAll(),
        lookup.getJenisPrestasi(),
        lookup.getTingkatPrestasi(),
      ]);
      if (!mounted) return;

      setState(() {
        _mahasiswaList =
            (results[0] as List).whereType<Mahasiswa>().where((m) => m.status == 'Aktif').toList();
        _jenisList = (results[1] as List).whereType<JenisPrestasi>().toList();
        _tingkatList = (results[2] as List).whereType<TingkatPrestasi>().toList();
        _loading = false;
      });

      final user = context.read<AuthProvider>().user;
      if (user?.isMahasiswa == true && user?.mahasiswaId != null) {
        setState(() => _mahasiswaId = user!.mahasiswaId);
      }
    } catch (e) {
      setState(() {
        _loading = false;
        _loadError = e.toString();
      });
    }
  }

  Future<void> _pickDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() {
        _tanggal =
            '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
      });
    }
  }

  Future<void> _pickFile() async {
    final result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['pdf', 'jpg', 'jpeg', 'png'],
    );
    if (result != null && result.files.single.path != null) {
      final file = File(result.files.single.path!);
      final size = await file.length();
      if (size > 2 * 1024 * 1024) {
        _showSnack('Ukuran sertifikat maksimal 2MB');
        return;
      }
      setState(() => _sertifikat = file);
    }
  }

  Future<void> _submit() async {
    if (_mahasiswaId == null) {
      _showSnack('Pilih mahasiswa');
      return;
    }
    if (_jenisId == null) {
      _showSnack('Pilih jenis prestasi');
      return;
    }
    if (_tingkatId == null) {
      _showSnack('Pilih tingkat prestasi');
      return;
    }
    if (_namaLomba.text.trim().isEmpty) {
      _showSnack('Nama lomba wajib diisi');
      return;
    }
    if (_tanggal == null) {
      _showSnack('Pilih tanggal lomba');
      return;
    }

    setState(() {
      _saving = true;
      _error = null;
    });

    try {
      await _repo.create(
        data: {
          'mahasiswa_id': _mahasiswaId,
          'jenis_id': _jenisId,
          'tingkat_id': _tingkatId,
          'nama_lomba': _namaLomba.text.trim(),
          'penyelenggara': _penyelenggara.text.trim(),
          'tanggal': _tanggal,
          'juara': _juara,
        },
        sertifikat: _sertifikat,
      );
      if (mounted) {
        Navigator.of(context).pop(true);
      }
    } catch (e) {
      setState(() {
        _saving = false;
        _error = e.toString();
      });
    }
  }

  void _showSnack(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: GradientAppBar(title: 'Tambah Prestasi'),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (_loading) return const LoadingView();
    if (_loadError != null) {
      return ErrorView(message: _loadError!, onRetry: _loadData);
    }

    final isMahasiswa = context.read<AuthProvider>().user?.isMahasiswa ?? false;
    Mahasiswa? selectedMahasiswa;
    for (final m in _mahasiswaList) {
      if (m.id == _mahasiswaId) {
        selectedMahasiswa = m;
        break;
      }
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        if (isMahasiswa)
          InputDecorator(
            decoration: const InputDecoration(
              labelText: 'Mahasiswa',
              prefixIcon: Icon(Icons.person_outline_rounded),
            ),
            child: Text(
              selectedMahasiswa == null
                  ? '-'
                  : '${selectedMahasiswa.nama} (${selectedMahasiswa.nim})',
              style: const TextStyle(fontWeight: FontWeight.w600),
            ),
          )
        else
          DropdownButtonFormField<int>(
            initialValue: _mahasiswaId,
            decoration: const InputDecoration(
              labelText: 'Mahasiswa',
              prefixIcon: Icon(Icons.person_outline_rounded),
            ),
            items: [
              for (final m in _mahasiswaList)
                DropdownMenuItem(
                  value: m.id,
                  child: Text('${m.nama} (${m.nim})', maxLines: 1, overflow: TextOverflow.ellipsis),
                ),
            ],
            onChanged: (v) => setState(() => _mahasiswaId = v),
          ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: DropdownButtonFormField<int>(
                initialValue: _jenisId,
                decoration: const InputDecoration(
                  labelText: 'Jenis',
                  prefixIcon: Icon(Icons.category_outlined),
                ),
                items: [
                  for (final j in _jenisList)
                    DropdownMenuItem(value: j.id, child: Text(j.namaJenis)),
                ],
                onChanged: (v) => setState(() => _jenisId = v),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: DropdownButtonFormField<int>(
                initialValue: _tingkatId,
                decoration: const InputDecoration(
                  labelText: 'Tingkat',
                  prefixIcon: Icon(Icons.flag_outlined),
                ),
                items: [
                  for (final t in _tingkatList)
                    DropdownMenuItem(value: t.id, child: Text(t.namaTingkat)),
                ],
                onChanged: (v) => setState(() => _tingkatId = v),
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        TextFormField(
          controller: _namaLomba,
          decoration: const InputDecoration(
            labelText: 'Nama Lomba',
            prefixIcon: Icon(Icons.emoji_events_outlined),
          ),
        ),
        const SizedBox(height: 12),
        TextFormField(
          controller: _penyelenggara,
          decoration: const InputDecoration(
            labelText: 'Penyelenggara',
            prefixIcon: Icon(Icons.business_rounded),
          ),
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: InkWell(
                onTap: _pickDate,
                borderRadius: BorderRadius.circular(14),
                child: InputDecorator(
                  decoration: const InputDecoration(
                    labelText: 'Tanggal',
                    prefixIcon: Icon(Icons.calendar_today_rounded),
                  ),
                  child: Text(
                    _tanggal ?? 'Pilih',
                    style: TextStyle(
                      color: _tanggal == null
                          ? Colors.grey.shade500
                          : Colors.black,
                    ),
                  ),
                ),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: DropdownButtonFormField<String>(
                initialValue: _juara,
                decoration: const InputDecoration(
                  labelText: 'Juara',
                  prefixIcon: Icon(Icons.workspace_premium_outlined),
                ),
                items: const [
                  DropdownMenuItem(value: 'Juara 1', child: Text('Juara 1')),
                  DropdownMenuItem(value: 'Juara 2', child: Text('Juara 2')),
                  DropdownMenuItem(value: 'Juara 3', child: Text('Juara 3')),
                  DropdownMenuItem(value: 'Finalis', child: Text('Finalis')),
                  DropdownMenuItem(value: 'Peserta', child: Text('Peserta')),
                ],
                onChanged: (v) => setState(() => _juara = v),
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        _SertifikatPicker(
          file: _sertifikat,
          onPick: _pickFile,
        ),
        if (_error != null) ...[
          const SizedBox(height: 16),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.red.shade50,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Row(
              children: [
                Icon(Icons.error_outline_rounded,
                    color: Colors.red.shade400, size: 20),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    _error!,
                    style: TextStyle(color: Colors.red.shade700, fontSize: 13),
                  ),
                ),
              ],
            ),
          ),
        ],
        const SizedBox(height: 24),
        GradientFilledButton(
          label: 'Simpan Prestasi',
          icon: Icons.save_rounded,
          loading: _saving,
          onPressed: _submit,
        ),
      ],
    );
  }
}

class _SertifikatPicker extends StatelessWidget {
  const _SertifikatPicker({required this.file, required this.onPick});

  final File? file;
  final VoidCallback onPick;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onPick,
      borderRadius: BorderRadius.circular(14),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: AppColors.primary.withValues(alpha: 0.06),
          borderRadius: BorderRadius.circular(14),
          border: Border.all(
            color: AppColors.primary.withValues(alpha: 0.4),
            style: BorderStyle.solid,
          ),
        ),
        child: Row(
          children: [
            Icon(
              file == null ? Icons.upload_file_rounded : Icons.check_circle_rounded,
              color: AppColors.primary,
              size: 28,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                file == null
                    ? 'Upload Sertifikat (PDF/JPG/PNG, maks 2MB)'
                    : file!.path.split('\\').last.split('/').last,
                style: TextStyle(
                  color: file == null ? Colors.grey.shade600 : Colors.black,
                  fontSize: 13.5,
                  fontWeight: file == null ? FontWeight.w400 : FontWeight.w600,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
