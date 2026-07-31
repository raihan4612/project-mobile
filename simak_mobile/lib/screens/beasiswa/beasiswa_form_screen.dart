import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/models/program_beasiswa.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/repositories/beasiswa_repository.dart';
import '../../data/repositories/mahasiswa_repository.dart';

class BeasiswaFormScreen extends StatefulWidget {
  const BeasiswaFormScreen({super.key});

  @override
  State<BeasiswaFormScreen> createState() => _BeasiswaFormScreenState();
}

class _BeasiswaFormScreenState extends State<BeasiswaFormScreen> {
  final _repo = BeasiswaRepository();

  List<Mahasiswa> _mahasiswaList = [];
  List<ProgramBeasiswa> _programList = [];
  bool _loading = true;
  String? _loadError;

  int? _mahasiswaId;
  int? _programId;
  String? _tanggal;
  final _keterangan = TextEditingController();

  bool _saving = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  @override
  void dispose() {
    _keterangan.dispose();
    super.dispose();
  }

  Future<void> _loadData() async {
    setState(() {
      _loading = true;
      _loadError = null;
    });

    try {
      final results = await Future.wait([
        MahasiswaRepository().getAll(),
        _repo.getAllProgramBeasiswa(),
      ]);
      if (!mounted) return;

      setState(() {
        _mahasiswaList =
            (results[0] as List).whereType<Mahasiswa>().where((m) => m.status == 'Aktif').toList();
        _programList =
            (results[1] as List).whereType<ProgramBeasiswa>().toList();
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
      firstDate: DateTime(2020),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() {
        _tanggal =
            '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
      });
    }
  }

  Future<void> _submit() async {
    if (_mahasiswaId == null) {
      _showSnack('Pilih mahasiswa');
      return;
    }
    if (_programId == null) {
      _showSnack('Pilih program beasiswa');
      return;
    }
    if (_tanggal == null) {
      _showSnack('Pilih tanggal pengajuan');
      return;
    }

    setState(() {
      _saving = true;
      _error = null;
    });

    try {
      await _repo.create({
        'mahasiswa_id': _mahasiswaId,
        'program_beasiswa_id': _programId,
        'tanggal_pengajuan': _tanggal,
        'keterangan':
            _keterangan.text.trim().isEmpty ? null : _keterangan.text.trim(),
      });
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
      appBar: GradientAppBar(title: 'Ajukan Beasiswa'),
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
                  child: Text(
                    '${m.nama} (${m.nim})',
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
            ],
            onChanged: (v) => setState(() => _mahasiswaId = v),
          ),
        const SizedBox(height: 12),
        DropdownButtonFormField<int>(
          initialValue: _programId,
          decoration: const InputDecoration(
            labelText: 'Program Beasiswa',
            prefixIcon: Icon(Icons.workspace_premium_outlined),
          ),
          items: [
            for (final p in _programList)
              DropdownMenuItem(
                value: p.id,
                child: Text(
                  p.namaBeasiswa,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
          ],
          onChanged: (v) => setState(() => _programId = v),
        ),
        const SizedBox(height: 12),
        InkWell(
          onTap: _pickDate,
          borderRadius: BorderRadius.circular(14),
          child: InputDecorator(
            decoration: const InputDecoration(
              labelText: 'Tanggal Pengajuan',
              prefixIcon: Icon(Icons.calendar_today_rounded),
            ),
            child: Text(
              _tanggal ?? 'Pilih',
              style: TextStyle(
                color: _tanggal == null ? Colors.grey.shade500 : Colors.black,
              ),
            ),
          ),
        ),
        const SizedBox(height: 12),
        TextFormField(
          controller: _keterangan,
          maxLines: 3,
          decoration: const InputDecoration(
            labelText: 'Keterangan',
            prefixIcon: Icon(Icons.notes_rounded),
          ),
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
          label: 'Simpan Pengajuan',
          icon: Icons.save_rounded,
          loading: _saving,
          onPressed: _submit,
        ),
      ],
    );
  }
}
