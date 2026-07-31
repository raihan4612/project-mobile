import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/program_beasiswa.dart';
import '../../data/providers/beasiswa_provider.dart';

class ProgramBeasiswaScreen extends StatefulWidget {
  const ProgramBeasiswaScreen({super.key});

  @override
  State<ProgramBeasiswaScreen> createState() => _ProgramBeasiswaScreenState();
}

class _ProgramBeasiswaScreenState extends State<ProgramBeasiswaScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) context.read<BeasiswaProvider>().loadPrograms();
    });
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<BeasiswaProvider>();

    return Scaffold(
      appBar: GradientAppBar(title: 'Program Beasiswa'),
      body: _buildBody(provider),
    );
  }

  Widget _buildBody(BeasiswaProvider provider) {
    if (provider.loadingPrograms) return const LoadingView();
    if (provider.error != null && provider.programs.isEmpty) {
      return ErrorView(message: provider.error!, onRetry: provider.loadPrograms);
    }
    if (provider.programs.isEmpty) {
      return const EmptyView(title: 'Belum ada program beasiswa');
    }

    return RefreshIndicator(
      onRefresh: provider.loadPrograms,
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: provider.programs.length,
        itemBuilder: (context, i) {
          final p = provider.programs[i];
          return _ProgramCard(program: p);
        },
      ),
    );
  }
}

class _ProgramCard extends StatelessWidget {
  const _ProgramCard({required this.program});

  final ProgramBeasiswa program;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  width: 44,
                  height: 44,
                  decoration: BoxDecoration(
                    gradient: AppColors.primaryGradient,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: const Icon(
                    Icons.workspace_premium_rounded,
                    color: Colors.white,
                    size: 24,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        program.namaBeasiswa,
                        style: const TextStyle(
                          fontWeight: FontWeight.w700,
                          fontSize: 15,
                        ),
                      ),
                      Text(
                        program.penyelenggara,
                        style: TextStyle(
                          color: Colors.grey.shade600,
                          fontSize: 12.5,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                _Info(
                  icon: Icons.calendar_month_rounded,
                  text: program.tahunAkademik ?? '-',
                ),
                const SizedBox(width: 16),
                _Info(
                  icon: Icons.redeem_rounded,
                  text: program.jumlahDana == null
                      ? 'Dana: -'
                      : 'Dana: Rp ${_formatDana(program.jumlahDana!)}',
                ),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 10,
                    vertical: 5,
                  ),
                  decoration: BoxDecoration(
                    color: AppColors.primary.withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    '${program.beasiswaCount} pengajuan',
                    style: const TextStyle(
                      color: AppColors.primary,
                      fontSize: 11.5,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  String _formatDana(String dana) {
    final n = double.tryParse(dana)?.toInt();
    if (n == null) return dana;
    final s = '$n';
    final buffer = StringBuffer();
    for (var i = 0; i < s.length; i++) {
      if (i > 0 && (s.length - i) % 3 == 0) buffer.write('.');
      buffer.write(s[i]);
    }
    return buffer.toString();
  }
}

class _Info extends StatelessWidget {
  const _Info({required this.icon, required this.text});

  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, color: Colors.grey.shade500, size: 16),
        const SizedBox(width: 4),
        Text(
          text,
          style: TextStyle(color: Colors.grey.shade700, fontSize: 12.5),
        ),
      ],
    );
  }
}
