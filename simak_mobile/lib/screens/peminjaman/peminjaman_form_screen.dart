import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/buku.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/repositories/buku_repository.dart';
import '../../data/repositories/mahasiswa_repository.dart';
import '../../data/repositories/peminjaman_repository.dart';

class PeminjamanFormScreen extends StatefulWidget {
  const PeminjamanFormScreen({super.key, this.initialBukuId});

  final int? initialBukuId;

  @override
  State<PeminjamanFormScreen> createState() => _PeminjamanFormScreenState();
}

class _PeminjamanFormScreenState extends State<PeminjamanFormScreen> {
  final _repo = PeminjamanRepository();

  List<Mahasiswa>? _mahasiswaList;
  List<Buku>? _bukuList;
  bool _loading = true;
  String? _loadError;

  int? _mahasiswaId;
  int? _bukuId;
  String? _tanggalPinjam;
  String? _tanggalKembali;
  final _catatan = TextEditingController();

  bool _saving = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  @override
  void dispose() {
    _catatan.dispose();
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
        BukuRepository().getAll(),
      ]);
      if (!mounted) return;

      setState(() {
        _mahasiswaList = (results[0] as List)
            .whereType<Mahasiswa>()
            .where((m) => m.status == 'Aktif')
            .toList();
        _bukuList = (results[1] as List)
            .whereType<Buku>()
            .where((b) => b.tersedia)
            .toList();
        if (widget.initialBukuId != null &&
            _bukuList!.any((b) => b.id == widget.initialBukuId)) {
          _bukuId = widget.initialBukuId;
        }
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

  Future<void> _pickDate(bool isPinjam) async {
    final initial = isPinjam
        ? DateTime.now()
        : DateTime.tryParse(_tanggalKembali ?? '') ??
            DateTime.now().add(const Duration(days: 7));

    final picked = await showDatePicker(
      context: context,
      initialDate: initial,
      firstDate: DateTime.now().subtract(const Duration(days: 30)),
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );
    if (picked != null) {
      setState(() {
        final value =
            '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
        if (isPinjam) {
          _tanggalPinjam = value;
        } else {
          _tanggalKembali = value;
        }
      });
    }
  }

  Future<void> _submit() async {
    if (_mahasiswaId == null) {
      _showSnack('Pilih mahasiswa terlebih dahulu');
      return;
    }
    if (_bukuId == null) {
      _showSnack('Pilih buku terlebih dahulu');
      return;
    }
    if (_tanggalPinjam == null) {
      _showSnack('Pilih tanggal pinjam');
      return;
    }
    if (_tanggalKembali == null) {
      _showSnack('Pilih tanggal kembali');
      return;
    }

    setState(() {
      _saving = true;
      _error = null;
    });

    try {
      await _repo.create({
        'mahasiswa_id': _mahasiswaId,
        'buku_id': _bukuId,
        'tanggal_pinjam': _tanggalPinjam,
        'tanggal_kembali_rencana': _tanggalKembali,
        'catatan': _catatan.text.trim().isEmpty ? null : _catatan.text.trim(),
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
      appBar: GradientAppBar(title: 'Buat Peminjaman'),
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
    for (final m in _mahasiswaList ?? []) {
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
              for (final m in _mahasiswaList ?? [])
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
          initialValue: _bukuId,
          decoration: const InputDecoration(
            labelText: 'Buku',
            prefixIcon: Icon(Icons.menu_book_rounded),
          ),
          items: [
            for (final b in _bukuList ?? [])
              DropdownMenuItem(
                value: b.id,
                child: Text(
                  '${b.judul} (stok ${b.jumlahTersedia})',
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
          ],
          onChanged: (v) => setState(() => _bukuId = v),
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: _dateField('Tanggal Pinjam', _tanggalPinjam, true),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _dateField('Tanggal Kembali', _tanggalKembali, false),
            ),
          ],
        ),
        const SizedBox(height: 12),
        TextFormField(
          controller: _catatan,
          maxLines: 3,
          decoration: const InputDecoration(
            labelText: 'Catatan',
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
                    style:
                        TextStyle(color: Colors.red.shade700, fontSize: 13),
                  ),
                ),
              ],
            ),
          ),
        ],
        const SizedBox(height: 24),
        GradientFilledButton(
          label: 'Simpan Peminjaman',
          icon: Icons.save_rounded,
          loading: _saving,
          onPressed: _submit,
        ),
      ],
    );
  }

  Widget _dateField(String label, String? value, bool isPinjam) {
    return InkWell(
      onTap: () => _pickDate(isPinjam),
      borderRadius: BorderRadius.circular(14),
      child: InputDecorator(
        decoration: InputDecoration(
          labelText: label,
          prefixIcon: const Icon(Icons.calendar_today_rounded),
        ),
        child: Text(
          value ?? 'Pilih',
          style: TextStyle(
            color: value == null ? Colors.grey.shade500 : Colors.black,
          ),
        ),
      ),
    );
  }
}
