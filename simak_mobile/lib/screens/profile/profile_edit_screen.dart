import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';

import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/repositories/mahasiswa_repository.dart';

class ProfileEditScreen extends StatefulWidget {
  const ProfileEditScreen({super.key, required this.mahasiswa});

  final Mahasiswa mahasiswa;

  @override
  State<ProfileEditScreen> createState() => _ProfileEditScreenState();
}

class _ProfileEditScreenState extends State<ProfileEditScreen> {
  final _formKey = GlobalKey<FormState>();

  late final TextEditingController _namaC;
  late final TextEditingController _tempatLahirC;
  late final TextEditingController _noHpC;
  late final TextEditingController _emailC;
  late final TextEditingController _alamatC;
  late final TextEditingController _kotaC;
  late final TextEditingController _provinsiC;
  late final TextEditingController _kodePosC;
  late final TextEditingController _prodiC;
  late final TextEditingController _fakultasC;
  late final TextEditingController _semesterC;
  late final TextEditingController _tahunMasukC;
  late final TextEditingController _ipkC;

  String? _jenisKelamin;
  String? _tanggalLahir;

  bool _saving = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    final m = widget.mahasiswa;
    _namaC = TextEditingController(text: m.nama);
    _tempatLahirC = TextEditingController(text: m.tempatLahir ?? '');
    _noHpC = TextEditingController(text: m.noHp ?? '');
    _emailC = TextEditingController(text: m.email ?? '');
    _alamatC = TextEditingController(text: m.alamat ?? '');
    _kotaC = TextEditingController(text: m.kota ?? '');
    _provinsiC = TextEditingController(text: m.provinsi ?? '');
    _kodePosC = TextEditingController(text: m.kodePos ?? '');
    _prodiC = TextEditingController(text: m.prodi ?? '');
    _fakultasC = TextEditingController(text: m.fakultas ?? '');
    _semesterC = TextEditingController(text: m.semester?.toString() ?? '');
    _tahunMasukC = TextEditingController(text: m.tahunMasuk ?? '');
    _ipkC = TextEditingController(text: m.ipk?.toString() ?? '');
    _jenisKelamin = m.jenisKelamin;
    _tanggalLahir = m.tanggalLahir;
  }

  @override
  void dispose() {
    _namaC.dispose();
    _tempatLahirC.dispose();
    _noHpC.dispose();
    _emailC.dispose();
    _alamatC.dispose();
    _kotaC.dispose();
    _provinsiC.dispose();
    _kodePosC.dispose();
    _prodiC.dispose();
    _fakultasC.dispose();
    _semesterC.dispose();
    _tahunMasukC.dispose();
    _ipkC.dispose();
    super.dispose();
  }

  Future<void> _pickTanggalLahir() async {
    final current = DateTime.tryParse(_tanggalLahir ?? '');
    final picked = await showDatePicker(
      context: context,
      initialDate: current ?? DateTime(2000),
      firstDate: DateTime(1950),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() {
        _tanggalLahir = '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
      });
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _saving = true;
      _error = null;
    });

    try {
      final data = <String, dynamic>{};
      void add(String key, dynamic value) {
        if (value != null && value.toString().trim().isNotEmpty) {
          data[key] = value;
        }
      }

      add('nama', _namaC.text.trim());
      add('jenis_kelamin', _jenisKelamin);
      add('tempat_lahir', _tempatLahirC.text.trim());
      add('tanggal_lahir', _tanggalLahir);
      add('no_hp', _noHpC.text.trim());
      add('email', _emailC.text.trim());
      add('alamat', _alamatC.text.trim());
      add('kota', _kotaC.text.trim());
      add('provinsi', _provinsiC.text.trim());
      add('kode_pos', _kodePosC.text.trim());
      add('prodi', _prodiC.text.trim());
      add('fakultas', _fakultasC.text.trim());
      add('semester', int.tryParse(_semesterC.text.trim()));
      add('tahun_masuk', _tahunMasukC.text.trim());
      add('ipk', double.tryParse(_ipkC.text.trim()));

      final updated = await MahasiswaRepository().update(widget.mahasiswa.id, data);
      if (!mounted) return;
      await context.read<AuthProvider>().updateProfile(
            nama: updated.nama,
            email: updated.email ?? '',
          );

      if (mounted) Navigator.of(context).pop(true);
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
      appBar: GradientAppBar(title: 'Edit Profil'),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            TextFormField(
              controller: _namaC,
              textCapitalization: TextCapitalization.words,
              decoration: const InputDecoration(
                labelText: 'Nama Lengkap',
                prefixIcon: Icon(Icons.badge_outlined),
              ),
              validator: (v) =>
                  (v == null || v.trim().isEmpty) ? 'Nama wajib diisi' : null,
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
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
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _tempatLahirC,
                    textCapitalization: TextCapitalization.words,
                    decoration: const InputDecoration(
                      labelText: 'Tempat Lahir',
                      prefixIcon: Icon(Icons.place_outlined),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: InkWell(
                    onTap: _pickTanggalLahir,
                    borderRadius: BorderRadius.circular(14),
                    child: InputDecorator(
                      decoration: const InputDecoration(
                        labelText: 'Tanggal Lahir',
                        prefixIcon: Icon(Icons.cake_outlined),
                      ),
                      child: Text(
                        _tanggalLahir ?? 'Pilih',
                        style: TextStyle(
                          color: _tanggalLahir == null
                              ? Colors.grey.shade500
                              : Colors.black,
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _noHpC,
              keyboardType: TextInputType.phone,
              decoration: const InputDecoration(
                labelText: 'No. HP',
                prefixIcon: Icon(Icons.phone_outlined),
              ),
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _emailC,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(
                labelText: 'Email',
                prefixIcon: Icon(Icons.mail_outline_rounded),
              ),
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _alamatC,
              maxLines: 3,
              textCapitalization: TextCapitalization.sentences,
              decoration: const InputDecoration(
                labelText: 'Alamat',
                prefixIcon: Icon(Icons.home_outlined),
              ),
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _kotaC,
                    textCapitalization: TextCapitalization.words,
                    decoration: const InputDecoration(
                      labelText: 'Kota',
                      prefixIcon: Icon(Icons.location_city_outlined),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _provinsiC,
                    textCapitalization: TextCapitalization.words,
                    decoration: const InputDecoration(
                      labelText: 'Provinsi',
                      prefixIcon: Icon(Icons.map_outlined),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _kodePosC,
              keyboardType: TextInputType.number,
              inputFormatters: [FilteringTextInputFormatter.digitsOnly],
              decoration: const InputDecoration(
                labelText: 'Kode Pos',
                prefixIcon: Icon(Icons.local_post_office_outlined),
              ),
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _prodiC,
                    textCapitalization: TextCapitalization.words,
                    decoration: const InputDecoration(
                      labelText: 'Prodi',
                      prefixIcon: Icon(Icons.school_outlined),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _fakultasC,
                    textCapitalization: TextCapitalization.words,
                    decoration: const InputDecoration(
                      labelText: 'Fakultas',
                      prefixIcon: Icon(Icons.account_balance_outlined),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _semesterC,
                    keyboardType: TextInputType.number,
                    inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                    decoration: const InputDecoration(
                      labelText: 'Semester',
                      prefixIcon: Icon(Icons.tag_rounded),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _tahunMasukC,
                    keyboardType: TextInputType.number,
                    inputFormatters: [
                      FilteringTextInputFormatter.digitsOnly,
                      LengthLimitingTextInputFormatter(4),
                    ],
                    decoration: const InputDecoration(
                      labelText: 'Tahun Masuk',
                      prefixIcon: Icon(Icons.calendar_month_outlined),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _ipkC,
              keyboardType:
                  const TextInputType.numberWithOptions(decimal: true),
              decoration: const InputDecoration(
                labelText: 'IPK',
                prefixIcon: Icon(Icons.star_outline_rounded),
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
              label: 'Simpan Perubahan',
              icon: Icons.save_rounded,
              loading: _saving,
              onPressed: _submit,
            ),
            const SizedBox(height: 16),
          ],
        ),
      ),
    );
  }
}
