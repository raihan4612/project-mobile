import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/dashboard_stats.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/providers/dashboard_provider.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) context.read<DashboardProvider>().load();
    });
  }

  @override
  Widget build(BuildContext context) {
    final dashboard = context.watch<DashboardProvider>();
    final auth = context.watch<AuthProvider>();
    final user = auth.user;

    return Scaffold(
      appBar: GradientAppBar(title: 'Sistem Informasi Akademik'),
      body: RefreshIndicator(
        onRefresh: () => dashboard.load(),
        child: _buildBody(dashboard, user?.nama ?? ''),
      ),
    );
  }

  Widget _buildBody(DashboardProvider dashboard, String nama) {
    if (dashboard.loading) return const LoadingView();
    if (dashboard.error != null) {
      return ErrorView(
        message: dashboard.error!,
        onRetry: dashboard.load,
      );
    }

    final stats = dashboard.stats!;

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Text(
          'Halo, $nama ',
          style: const TextStyle(
            fontSize: 22,
            fontWeight: FontWeight.w800,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          'Ringkasan data akademik saat ini',
          style: TextStyle(fontSize: 13.5, color: Colors.grey.shade600),
        ),
        const SizedBox(height: 16),
        _StatGrid(stats: stats),
        const SizedBox(height: 20),
        _SectionGroup(
          title: 'Peminjaman Saya',
          icon: Icons.swap_horiz_rounded,
          accent: AppColors.secondary,
          children: [
            _StatusSection(
              title: 'Status Peminjaman',
              icon: Icons.swap_horiz_rounded,
              entries: [
                ('Dipinjam', stats.peminjamanDipinjam, AppColors.primary),
                ('Dikembalikan', stats.peminjamanDikembalikan,
                    Colors.green.shade600),
                ('Terlambat', stats.peminjamanTerlambat, Colors.orange.shade700),
              ],
            ),
            const SizedBox(height: 16),
            const Text(
              'Peminjaman Terbaru',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
            ),
            const SizedBox(height: 8),
            if (dashboard.peminjamanTerbaru.isEmpty)
              const EmptyView(title: 'Belum ada peminjaman')
            else
              Column(
                children: [
                  for (final pj in dashboard.peminjamanTerbaru)
                    ListTile(
                      leading: CircleAvatar(
                        backgroundColor:
                            AppColors.secondary.withValues(alpha: 0.12),
                        child: const Icon(
                          Icons.menu_book_rounded,
                          color: AppColors.secondary,
                          size: 20,
                        ),
                      ),
                      title: Text(
                        pj.buku?.judul ?? '-',
                        style: const TextStyle(fontWeight: FontWeight.w600),
                      ),
                      subtitle: Text(
                        '${pj.mahasiswa?.nama ?? '-'} • ${pj.kodePeminjaman}',
                      ),
                      trailing: _StatusChip(
                        label: pj.status ?? '-',
                        color: pj.status == 'Dikembalikan'
                            ? Colors.green.shade600
                            : pj.status == 'Terlambat'
                                ? Colors.orange.shade700
                                : AppColors.primary,
                      ),
                    ),
                ],
              ),
          ],
        ),
        const SizedBox(height: 16),
        _SectionGroup(
          title: 'Prestasi Saya',
          icon: Icons.emoji_events_rounded,
          accent: AppColors.primary,
          children: [
            _StatusSection(
              title: 'Status Prestasi',
              icon: Icons.emoji_events_rounded,
              entries: [
                ('Pending', stats.prestasiPending, Colors.orange.shade700),
                ('Disetujui', stats.prestasiDisetujui, Colors.green.shade600),
                ('Ditolak', stats.prestasiDitolak, Colors.red.shade400),
              ],
            ),
            const SizedBox(height: 16),
            const Text(
              'Prestasi Terbaru',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
            ),
            const SizedBox(height: 8),
            if (dashboard.prestasiTerbaru.isEmpty)
              const EmptyView(title: 'Belum ada prestasi')
            else
              Column(
                children: [
                  for (final p in dashboard.prestasiTerbaru)
                    ListTile(
                      leading: CircleAvatar(
                        backgroundColor:
                            AppColors.primary.withValues(alpha: 0.12),
                        child: const Icon(
                          Icons.emoji_events_rounded,
                          color: AppColors.primary,
                          size: 20,
                        ),
                      ),
                      title: Text(
                        p.namaLomba ?? '-',
                        style: const TextStyle(fontWeight: FontWeight.w600),
                      ),
                      subtitle: Text(p.mahasiswa?.nama ?? '-'),
                      trailing: _StatusChip(
                        label: p.statusVerifikasi ?? '-',
                        color: p.statusVerifikasi == 'Disetujui'
                            ? Colors.green.shade600
                            : p.statusVerifikasi == 'Ditolak'
                                ? Colors.red.shade400
                                : Colors.orange.shade700,
                      ),
                    ),
                ],
              ),
          ],
        ),
        const SizedBox(height: 16),
      ],
    );
  }
}

class _StatGrid extends StatelessWidget {
  const _StatGrid({required this.stats});

  final DashboardStats stats;

  @override
  Widget build(BuildContext context) {
    final items = [
      ('Mahasiswa', stats.mahasiswa, Icons.person_rounded),
      ('Buku', stats.buku, Icons.menu_book_rounded),
      ('Peminjaman', stats.peminjaman, Icons.swap_horiz_rounded),
      ('Prestasi', stats.prestasi, Icons.emoji_events_rounded),
      ('Program Beasiswa', stats.programBeasiswa, Icons.workspace_premium_rounded),
      ('Beasiswa', stats.beasiswa, Icons.redeem_rounded),
    ];

    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 3,
        mainAxisSpacing: 10,
        crossAxisSpacing: 10,
        childAspectRatio: 1.0,
      ),
      itemCount: items.length,
      itemBuilder: (context, i) {
        return _GradientStatCard(
          label: items[i].$1,
          value: items[i].$2,
          icon: items[i].$3,
        );
      },
    );
  }
}

class _GradientStatCard extends StatelessWidget {
  const _GradientStatCard({
    required this.label,
    required this.value,
    required this.icon,
  });

  final String label;
  final dynamic value;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        gradient: AppColors.primaryGradient,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: AppColors.gradientEnd.withValues(alpha: 0.3),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Icon(icon, color: Colors.white, size: 20),
          Text(
            '$value',
            style: const TextStyle(
              color: Colors.white,
              fontSize: 21,
              fontWeight: FontWeight.w800,
            ),
          ),
          Text(
            label,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: TextStyle(
              color: Colors.white.withValues(alpha: 0.85),
              fontSize: 11,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }
}

class _SectionGroup extends StatelessWidget {
  const _SectionGroup({
    required this.title,
    required this.icon,
    required this.accent,
    required this.children,
  });

  final String title;
  final IconData icon;
  final Color accent;
  final List<Widget> children;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: accent.withValues(alpha: 0.35), width: 1.4),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(icon, color: accent, size: 20),
              const SizedBox(width: 8),
              Text(
                title,
                style: const TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w800,
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          ...children,
        ],
      ),
    );
  }
}

class _StatusSection extends StatelessWidget {
  const _StatusSection({
    required this.title,
    required this.icon,
    required this.entries,
  });

  final String title;
  final IconData icon;
  final List<(String, int, Color)> entries;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, color: AppColors.primary, size: 20),
                const SizedBox(width: 8),
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                for (final entry in entries) ...[
                  Expanded(child: _MiniStat(label: entry.$1, value: entry.$2, color: entry.$3)),
                  if (entry != entries.last) const SizedBox(width: 8),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _MiniStat extends StatelessWidget {
  const _MiniStat({
    required this.label,
    required this.value,
    required this.color,
  });

  final String label;
  final int value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(
          '$value',
          style: TextStyle(
            fontSize: 20,
            fontWeight: FontWeight.w800,
            color: color,
          ),
        ),
        const SizedBox(height: 2),
        Text(
          label,
          style: TextStyle(fontSize: 11.5, color: Colors.grey.shade600),
        ),
      ],
    );
  }
}

class _StatusChip extends StatelessWidget {
  const _StatusChip({required this.label, required this.color});

  final String label;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontSize: 11.5,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}
