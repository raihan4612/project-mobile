import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/repositories/mahasiswa_repository.dart';
import '../login_screen.dart';
import 'profile_edit_screen.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  Mahasiswa? _mahasiswa;
  bool _loading = false;
  String? _error;

  bool get _isMahasiswa =>
      context.read<AuthProvider>().user?.isMahasiswa ?? false;

  int? get _mahasiswaId => context.read<AuthProvider>().user?.mahasiswaId;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) _load();
    });
  }

  Future<void> _load() async {
    if (_mahasiswaId == null) return;
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final m = await MahasiswaRepository().show(_mahasiswaId!);
      if (mounted) setState(() => _mahasiswa = m);
    } catch (e) {
      if (mounted) setState(() => _error = e.toString());
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user;

    return Scaffold(
      appBar: GradientAppBar(
        title: 'Profile',
        actions: [
          if (_isMahasiswa && _mahasiswa != null)
            IconButton(
              icon: const Icon(Icons.edit_rounded),
              tooltip: 'Edit Profil',
              onPressed: _openEdit,
            ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          _HeaderCard(nama: user?.nama ?? '-', email: user?.email ?? '-'),
          if (_isMahasiswa) ...[
            const SizedBox(height: 16),
            if (_loading)
              const Padding(
                padding: EdgeInsets.symmetric(vertical: 32),
                child: LoadingView(),
              )
            else if (_error != null)
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 16),
                child: ErrorView(message: _error!, onRetry: _load),
              )
            else
              _Sections(mahasiswa: _mahasiswa),
          ],
          const SizedBox(height: 24),
          OutlinedButton.icon(
            onPressed: () => _logout(context),
            style: OutlinedButton.styleFrom(
              foregroundColor: Colors.red.shade400,
              side: BorderSide(color: Colors.red.shade400),
              minimumSize: const Size.fromHeight(48),
            ),
            icon: const Icon(Icons.logout_rounded),
            label: const Text('Keluar'),
          ),
          const SizedBox(height: 16),
        ],
      ),
    );
  }

  Future<void> _openEdit() async {
    final m = _mahasiswa;
    if (m == null) return;

    final saved = await Navigator.of(context).push<bool>(
      MaterialPageRoute(builder: (_) => ProfileEditScreen(mahasiswa: m)),
    );
    if (saved == true && mounted) _load();
  }

  Future<void> _logout(BuildContext context) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Keluar'),
        content: const Text('Apakah Anda yakin ingin keluar?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          FilledButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Keluar'),
          ),
        ],
      ),
    );

    if (confirm == true && context.mounted) {
      await context.read<AuthProvider>().logout();
      if (context.mounted) {
        Navigator.of(context).pushAndRemoveUntil(
          MaterialPageRoute(builder: (_) => const LoginScreen()),
          (route) => false,
        );
      }
    }
  }
}

class _HeaderCard extends StatelessWidget {
  const _HeaderCard({required this.nama, required this.email});

  final String nama;
  final String email;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(24),
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
          Center(
            child: CircleAvatar(
              radius: 40,
              backgroundColor: Colors.white.withValues(alpha: 0.25),
              child: Text(
                nama.isNotEmpty ? nama[0].toUpperCase() : '?',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 36,
                  fontWeight: FontWeight.w800,
                ),
              ),
            ),
          ),
          const SizedBox(height: 14),
          Text(
            nama,
            textAlign: TextAlign.left,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            email,
            textAlign: TextAlign.left,
            style: TextStyle(
              color: Colors.white.withValues(alpha: 0.85),
              fontSize: 14,
            ),
          ),
        ],
      ),
    );
  }
}

class _Sections extends StatelessWidget {
  const _Sections({required this.mahasiswa});

  final Mahasiswa? mahasiswa;

  @override
  Widget build(BuildContext context) {
    final m = mahasiswa;

    return Column(
      children: [
        _InfoSection(
          icon: Icons.badge_outlined,
          title: 'Identitas Pribadi',
          rows: [
            ('NIM', m?.nim ?? '-'),
            ('Nama', m?.nama ?? '-'),
            ('Jenis Kelamin', _jenisKelamin(m?.jenisKelamin)),
            ('Tempat Lahir', m?.tempatLahir ?? '-'),
            ('Tanggal Lahir', _formatDate(m?.tanggalLahir)),
          ],
        ),
        const SizedBox(height: 12),
        _InfoSection(
          icon: Icons.contact_mail_outlined,
          title: 'Kontak & Alamat',
          rows: [
            ('No. HP', m?.noHp ?? '-'),
            ('Email', m?.email ?? '-'),
            ('Alamat', m?.alamat ?? '-'),
            ('Kota', m?.kota ?? '-'),
            ('Provinsi', m?.provinsi ?? '-'),
            ('Kode Pos', m?.kodePos ?? '-'),
          ],
        ),
        const SizedBox(height: 12),
        _InfoSection(
          icon: Icons.school_outlined,
          title: 'Data Akademik',
          rows: [
            ('Prodi', m?.prodi ?? '-'),
            ('Fakultas', m?.fakultas ?? '-'),
            ('Semester', '${m?.semester ?? '-'}'),
            ('Tahun Masuk', m?.tahunMasuk ?? '-'),
            ('Status', m?.status ?? '-'),
            ('IPK', m?.ipk?.toString() ?? '-'),
          ],
        ),
      ],
    );
  }

  String _jenisKelamin(String? value) {
    if (value == 'L') return 'Laki-laki';
    if (value == 'P') return 'Perempuan';
    return value ?? '-';
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

class _InfoSection extends StatelessWidget {
  const _InfoSection({
    required this.icon,
    required this.title,
    required this.rows,
  });

  final IconData icon;
  final String title;
  final List<(String, String)> rows;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.zero,
      child: ExpansionTile(
        leading: Icon(icon, color: AppColors.primary),
        title: Text(
          title,
          style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15),
        ),
        shape: const RoundedRectangleBorder(),
        collapsedShape: const RoundedRectangleBorder(),
        childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 12),
        expandedCrossAxisAlignment: CrossAxisAlignment.start,
        children: [
          for (final (label, value) in rows)
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 5),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SizedBox(
                    width: 115,
                    child: Text(
                      label,
                      style: TextStyle(
                        color: Colors.grey.shade600,
                        fontSize: 12.5,
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
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
            ),
        ],
      ),
    );
  }
}
