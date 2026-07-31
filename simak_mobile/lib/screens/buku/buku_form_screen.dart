import 'package:flutter/material.dart';

import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/buku.dart';
import '../../data/repositories/buku_repository.dart';

class BukuFormScreen extends StatefulWidget {
  const BukuFormScreen({super.key, this.buku});

  final Buku? buku;

  @override
  State<BukuFormScreen> createState() => _BukuFormScreenState();
}

class _BukuFormScreenState extends State<BukuFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _repo = BukuRepository();

  late final TextEditingController _kode;
  late final TextEditingController _judul;
  late final TextEditingController _pengarang;
  late final TextEditingController _penerbit;
  late final TextEditingController _tahunTerbit;
  late final TextEditingController _kategori;
  late final TextEditingController _jumlahStok;
  late final TextEditingController _deskripsi;

  bool _saving = false;
  String? _error;
  bool get _isEdit => widget.buku != null;

  @override
  void initState() {
    super.initState();
    final b = widget.buku;
    _kode = TextEditingController(text: b?.kodeBuku ?? '');
    _judul = TextEditingController(text: b?.judul ?? '');
    _pengarang = TextEditingController(text: b?.pengarang ?? '');
    _penerbit = TextEditingController(text: b?.penerbit ?? '');
    _tahunTerbit = TextEditingController(text: b?.tahunTerbit ?? '');
    _kategori = TextEditingController(text: b?.kategori ?? '');
    _jumlahStok = TextEditingController(text: b?.jumlahStok.toString() ?? '');
    _deskripsi = TextEditingController(text: b?.deskripsi ?? '');
  }

  @override
  void dispose() {
    for (final c in [
      _kode, _judul, _pengarang, _penerbit, _tahunTerbit, _kategori,
      _jumlahStok, _deskripsi,
    ]) {
      c.dispose();
    }
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _saving = true;
      _error = null;
    });

    final data = <String, dynamic>{
      'kode_buku': _kode.text.trim(),
      'judul': _judul.text.trim(),
      'pengarang': _pengarang.text.trim(),
      'penerbit': _penerbit.text.trim(),
      'tahun_terbit': _tahunTerbit.text.trim(),
      'kategori': _kategori.text.trim(),
      'jumlah_stok': int.tryParse(_jumlahStok.text.trim()) ?? 0,
      'deskripsi': _deskripsi.text.trim(),
    };

    try {
      if (_isEdit) {
        await _repo.update(widget.buku!.id, data);
      } else {
        await _repo.create(data);
      }
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: GradientAppBar(title: _isEdit ? 'Edit Buku' : 'Tambah Buku'),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            TextFormField(
              controller: _kode,
              decoration: const InputDecoration(
                labelText: 'Kode Buku',
                prefixIcon: Icon(Icons.tag_rounded),
              ),
              validator: (v) => (v == null || v.isEmpty) ? 'Kode buku wajib diisi' : null,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _judul,
              decoration: const InputDecoration(
                labelText: 'Judul',
                prefixIcon: Icon(Icons.menu_book_rounded),
              ),
              validator: (v) => (v == null || v.isEmpty) ? 'Judul wajib diisi' : null,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _pengarang,
              decoration: const InputDecoration(
                labelText: 'Pengarang',
                prefixIcon: Icon(Icons.person_outline_rounded),
              ),
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _penerbit,
              decoration: const InputDecoration(
                labelText: 'Penerbit',
                prefixIcon: Icon(Icons.business_rounded),
              ),
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _tahunTerbit,
                    decoration: const InputDecoration(
                      labelText: 'Tahun Terbit',
                      prefixIcon: Icon(Icons.calendar_month_rounded),
                    ),
                    keyboardType: TextInputType.number,
                    validator: (v) {
                      if (v == null || v.isEmpty) return null;
                      if (v.length != 4) return '4 digit';
                      return null;
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _kategori,
                    decoration: const InputDecoration(
                      labelText: 'Kategori',
                      prefixIcon: Icon(Icons.category_outlined),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _jumlahStok,
              decoration: const InputDecoration(
                labelText: 'Jumlah Stok',
                prefixIcon: Icon(Icons.inventory_2_outlined),
              ),
              keyboardType: TextInputType.number,
              validator: (v) {
                final n = int.tryParse(v ?? '');
                if (n == null || n < 0) return 'Jumlah stok tidak valid';
                return null;
              },
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _deskripsi,
              decoration: const InputDecoration(
                labelText: 'Deskripsi',
                prefixIcon: Icon(Icons.description_outlined),
              ),
              maxLines: 3,
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
                        style: TextStyle(
                          color: Colors.red.shade700,
                          fontSize: 13,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
            const SizedBox(height: 24),
            GradientFilledButton(
              label: _isEdit ? 'Simpan Perubahan' : 'Simpan',
              icon: Icons.save_rounded,
              loading: _saving,
              onPressed: _submit,
            ),
          ],
        ),
      ),
    );
  }
}
