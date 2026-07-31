import 'package:flutter/material.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/repositories/mahasiswa_repository.dart';

class MahasiswaFormScreen extends StatefulWidget {
  const MahasiswaFormScreen({super.key, this.mahasiswa});

  final Mahasiswa? mahasiswa;

  @override
  State<MahasiswaFormScreen> createState() => _MahasiswaFormScreenState();
}

class _MahasiswaFormScreenState extends State<MahasiswaFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _repo = MahasiswaRepository();

  late final TextEditingController _nim;
  late final TextEditingController _nama;
  late final TextEditingController _tempatLahir;
  late final TextEditingController _alamat;
  late final TextEditingController _kota;
  late final TextEditingController _provinsi;
  late final TextEditingController _kodePos;
  late final TextEditingController _noHp;
  late final TextEditingController _email;
  late final TextEditingController _prodi;
  late final TextEditingController _fakultas;
  late final TextEditingController _semester;
  late final TextEditingController _tahunMasuk;
  late final TextEditingController _ipk;

  String? _jenisKelamin;
  String? _status;
  String? _tanggalLahir;

  bool _saving = false;
  String? _error;
  bool get _isEdit => widget.mahasiswa != null;

  @override
  void initState() {
    super.initState();
    final m = widget.mahasiswa;
    _nim = TextEditingController(text: m?.nim ?? '');
    _nama = TextEditingController(text: m?.nama ?? '');
    _tempatLahir = TextEditingController(text: m?.tempatLahir ?? '');
    _alamat = TextEditingController(text: m?.alamat ?? '');
    _kota = TextEditingController(text: m?.kota ?? '');
    _provinsi = TextEditingController(text: m?.provinsi ?? '');
    _kodePos = TextEditingController(text: m?.kodePos ?? '');
    _noHp = TextEditingController(text: m?.noHp ?? '');
    _email = TextEditingController(text: m?.email ?? '');
    _prodi = TextEditingController(text: m?.prodi ?? '');
    _fakultas = TextEditingController(text: m?.fakultas ?? '');
    _semester = TextEditingController(text: m?.semester?.toString() ?? '');
    _tahunMasuk = TextEditingController(text: m?.tahunMasuk ?? '');
    _ipk = TextEditingController(text: m?.ipk?.toString() ?? '');
    _jenisKelamin = m?.jenisKelamin;
    _status = m?.status ?? 'Aktif';
    _tanggalLahir = m?.tanggalLahir;
  }

  @override
  void dispose() {
    for (final c in [
      _nim, _nama, _tempatLahir, _alamat, _kota, _provinsi, _kodePos,
      _noHp, _email, _prodi, _fakultas, _semester, _tahunMasuk, _ipk,
    ]) {
      c.dispose();
    }
    super.dispose();
  }

  Future<void> _pickDate() async {
    final initial = DateTime.tryParse(_tanggalLahir ?? '');
    final picked = await showDatePicker(
      context: context,
      initialDate: initial ?? DateTime(2000),
      firstDate: DateTime(1960),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() {
        _tanggalLahir =
            '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
      });
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _saving = true;
      _error = null;
    });

    final data = <String, dynamic>{
      'nim': _nim.text.trim(),
      'nama': _nama.text.trim(),
      'jenis_kelamin': _jenisKelamin,
      'tanggal_lahir': _tanggalLahir,
      'tempat_lahir': _tempatLahir.text.trim(),
      'alamat': _alamat.text.trim(),
      'kota': _kota.text.trim(),
      'provinsi': _provinsi.text.trim(),
      'kode_pos': _kodePos.text.trim(),
      'no_hp': _noHp.text.trim(),
      'email': _email.text.trim(),
      'prodi': _prodi.text.trim(),
      'fakultas': _fakultas.text.trim(),
      'semester': int.tryParse(_semester.text.trim()),
      'tahun_masuk': _tahunMasuk.text.trim(),
      'status': _status,
      'ipk': double.tryParse(_ipk.text.trim()),
    };

    try {
      if (_isEdit) {
        await _repo.update(widget.mahasiswa!.id, data);
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
      appBar: GradientAppBar(title: _isEdit ? 'Edit Mahasiswa' : 'Tambah Mahasiswa'),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            const _SectionHeader(title: 'Data Pribadi'),
            _field(
              _nim,
              'NIM',
              icon: Icons.badge_outlined,
              validator: (v) => (v == null || v.isEmpty) ? 'NIM wajib diisi' : null,
            ),
            _field(
              _nama,
              'Nama Lengkap',
              icon: Icons.person_outline_rounded,
              validator: (v) => (v == null || v.isEmpty) ? 'Nama wajib diisi' : null,
            ),
            Row(
              children: [
                Expanded(
                  child: DropdownButtonFormField<String>(
                    initialValue: _jenisKelamin,
                    decoration: const InputDecoration(
                      labelText: 'Jenis Kelamin',
                      prefixIcon: Icon(Icons.wc_rounded),
                    ),
                    items: const [
                      DropdownMenuItem(value: 'L', child: Text('Laki-laki')),
                      DropdownMenuItem(value: 'P', child: Text('Perempuan')),
                    ],
                    onChanged: (v) => setState(() => _jenisKelamin = v),
                    validator: (v) => v == null ? 'Pilih jenis kelamin' : null,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _dateField('Tanggal Lahir', _tanggalLahir, _pickDate),
                ),
              ],
            ),
            _field(_tempatLahir, 'Tempat Lahir', icon: Icons.place_outlined),
            const _SectionHeader(title: 'Alamat'),
            _field(_alamat, 'Alamat', icon: Icons.home_outlined),
            Row(
              children: [
                Expanded(child: _field(_kota, 'Kota', icon: Icons.location_city_rounded)),
                const SizedBox(width: 12),
                Expanded(child: _field(_kodePos, 'Kode Pos', icon: Icons.numbers_rounded)),
              ],
            ),
            _field(_provinsi, 'Provinsi', icon: Icons.map_outlined),
            const _SectionHeader(title: 'Akademik'),
            _field(_prodi, 'Program Studi', icon: Icons.school_outlined),
            _field(_fakultas, 'Fakultas', icon: Icons.account_balance_rounded),
            Row(
              children: [
                Expanded(
                  child: _field(
                    _semester,
                    'Semester',
                    icon: Icons.exposure_plus_1_rounded,
                    keyboardType: TextInputType.number,
                    validator: (v) {
                      if (v == null || v.isEmpty) return null;
                      final n = int.tryParse(v);
                      if (n == null || n < 1 || n > 14) {
                        return '1-14';
                      }
                      return null;
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(child: _field(_tahunMasuk, 'Tahun Masuk', icon: Icons.calendar_month_rounded)),
              ],
            ),
            _field(
              _ipk,
              'IPK',
              icon: Icons.star_outline_rounded,
              keyboardType: const TextInputType.numberWithOptions(decimal: true),
              validator: (v) {
                if (v == null || v.isEmpty) return null;
                final n = double.tryParse(v);
                if (n == null || n < 0 || n > 4) return 'IPK 0 - 4';
                return null;
              },
            ),
            _field(_email, 'Email', icon: Icons.mail_outline_rounded,
                keyboardType: TextInputType.emailAddress,
                validator: (v) =>
                    (v != null && v.isNotEmpty && !v.contains('@'))
                        ? 'Format email tidak valid'
                        : null),
            _field(_noHp, 'No. HP', icon: Icons.phone_outlined,
                keyboardType: TextInputType.phone),
            DropdownButtonFormField<String>(
              initialValue: _status,
              decoration: const InputDecoration(
                labelText: 'Status',
                prefixIcon: Icon(Icons.check_circle_outline_rounded),
              ),
              items: const [
                DropdownMenuItem(value: 'Aktif', child: Text('Aktif')),
                DropdownMenuItem(value: 'Cuti', child: Text('Cuti')),
                DropdownMenuItem(value: 'Lulus', child: Text('Lulus')),
                DropdownMenuItem(value: 'Dropout', child: Text('Dropout')),
              ],
              onChanged: (v) => setState(() => _status = v),
            ),
            if (_error != null) ...[
              const SizedBox(height: 16),
              _errorBanner(_error!),
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

  Widget _field(
    TextEditingController controller,
    String label, {
    IconData? icon,
    TextInputType? keyboardType,
    String? Function(String?)? validator,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: TextFormField(
        controller: controller,
        keyboardType: keyboardType,
        decoration: InputDecoration(
          labelText: label,
          prefixIcon: icon != null ? Icon(icon) : null,
        ),
        validator: validator,
      ),
    );
  }

  Widget _dateField(String label, String? value, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(14),
      child: InputDecorator(
        decoration: const InputDecoration(
          labelText: 'Tanggal Lahir',
          prefixIcon: Icon(Icons.calendar_today_rounded),
        ),
        child: Text(
          value ?? 'Pilih tanggal',
          style: TextStyle(
            color: value == null ? Colors.grey.shade500 : Colors.black,
          ),
        ),
      ),
    );
  }
}

class _SectionHeader extends StatelessWidget {
  const _SectionHeader({required this.title});

  final String title;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 8, bottom: 10),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 14,
          fontWeight: FontWeight.w700,
          color: AppColors.primary,
        ),
      ),
    );
  }
}

Widget _errorBanner(String message) {
  return Container(
    padding: const EdgeInsets.all(12),
    decoration: BoxDecoration(
      color: Colors.red.shade50,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.error_outline_rounded, color: Colors.red.shade400, size: 20),
        const SizedBox(width: 8),
        Expanded(
          child: Text(
            message,
            style: TextStyle(color: Colors.red.shade700, fontSize: 13),
          ),
        ),
      ],
    ),
  );
}
