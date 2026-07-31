import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/peminjaman.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/providers/peminjaman_provider.dart';
import 'peminjaman_form_screen.dart';

class PeminjamanListScreen extends StatefulWidget {
  const PeminjamanListScreen({super.key});

  @override
  State<PeminjamanListScreen> createState() => _PeminjamanListScreenState();
}

class _PeminjamanListScreenState extends State<PeminjamanListScreen> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) context.read<PeminjamanProvider>().load();
    });
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      context.read<PeminjamanProvider>().loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<PeminjamanProvider>();
    final isMahasiswa =
        context.watch<AuthProvider>().user?.isMahasiswa ?? false;

    return Scaffold(
      appBar: GradientAppBar(title: 'Peminjaman'),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _searchController,
              onChanged: (v) => context.read<PeminjamanProvider>().setSearch(v),
              decoration: InputDecoration(
                hintText: 'Cari kode / nama / NIM...',
                prefixIcon: const Icon(Icons.search_rounded),
                suffixIcon: _searchController.text.isEmpty
                    ? null
                    : IconButton(
                        icon: const Icon(Icons.clear_rounded),
                        onPressed: () {
                          _searchController.clear();
                          context.read<PeminjamanProvider>().setSearch('');
                        },
                      ),
              ),
            ),
          ),
          Expanded(child: _buildList(provider, isMahasiswa)),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _openForm(),
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
        child: const Icon(Icons.add_rounded),
      ),
    );
  }

  Widget _buildList(PeminjamanProvider provider, bool isMahasiswa) {
    if (provider.loading) return const LoadingView();
    if (provider.error != null && provider.items.isEmpty) {
      return ErrorView(message: provider.error!, onRetry: provider.load);
    }
    if (provider.items.isEmpty) {
      return const EmptyView(title: 'Tidak ada data peminjaman');
    }

    return RefreshIndicator(
      onRefresh: () => provider.load(showLoader: false),
      child: ListView.builder(
        controller: _scrollController,
        padding: const EdgeInsets.fromLTRB(16, 0, 16, 96),
        itemCount: provider.items.length + (provider.hasMore ? 1 : 0),
        itemBuilder: (context, i) {
          if (i >= provider.items.length) {
            return const Padding(
              padding: EdgeInsets.all(16),
              child: Center(
                child: CircularProgressIndicator(color: AppColors.primary),
              ),
            );
          }
          final pj = provider.items[i];
          return _PeminjamanCard(
            peminjaman: pj,
            canDelete: !isMahasiswa,
            onKembalikan: () => _kembalikan(pj),
            onDelete: () => _confirmDelete(pj),
          );
        },
      ),
    );
  }

  void _openForm() {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => const PeminjamanFormScreen()),
    );
  }

  Future<void> _kembalikan(Peminjaman pj) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Pengembalian'),
        content: Text(
          'Konfirmasi pengembalian buku "${pj.buku?.judul ?? ''}"?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          FilledButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Ya, Kembalikan'),
          ),
        ],
      ),
    );

    if (confirm == true && context.mounted) {
      try {
        await context.read<PeminjamanProvider>().pengembalian(pj.id);
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Buku berhasil dikembalikan')),
          );
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

  Future<void> _confirmDelete(Peminjaman pj) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Hapus Peminjaman'),
        content: Text('Hapus peminjaman ${pj.kodePeminjaman}?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          FilledButton(
            style: FilledButton.styleFrom(backgroundColor: Colors.red.shade400),
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );

    if (confirm == true && context.mounted) {
      try {
        await context.read<PeminjamanProvider>().delete(pj.id);
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Peminjaman berhasil dihapus')),
          );
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
}

class _PeminjamanCard extends StatelessWidget {
  const _PeminjamanCard({
    required this.peminjaman,
    required this.canDelete,
    required this.onKembalikan,
    required this.onDelete,
  });

  final Peminjaman peminjaman;
  final bool canDelete;
  final VoidCallback onKembalikan;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    final color = _statusColor(peminjaman.status);

    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 10,
                    vertical: 5,
                  ),
                  decoration: BoxDecoration(
                    color: color.withValues(alpha: 0.12),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    peminjaman.status ?? '-',
                    style: TextStyle(
                      color: color,
                      fontSize: 11.5,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
                const Spacer(),
                Text(
                  peminjaman.kodePeminjaman,
                  style: TextStyle(
                    color: Colors.grey.shade500,
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 10),
            Row(
              children: [
                Icon(Icons.menu_book_rounded,
                    color: AppColors.primary, size: 20),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    peminjaman.buku?.judul ?? '-',
                    style: const TextStyle(fontWeight: FontWeight.w600),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 6),
            Row(
              children: [
                Icon(Icons.person_outline_rounded,
                    color: Colors.grey.shade500, size: 18),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    '${peminjaman.mahasiswa?.nama ?? '-'} (${peminjaman.mahasiswa?.nim ?? '-'})',
                    style: TextStyle(
                      color: Colors.grey.shade700,
                      fontSize: 13,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 6),
            Row(
              children: [
                Icon(Icons.event_rounded, color: Colors.grey.shade500, size: 18),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    'Pinjam: ${_shortDate(peminjaman.tanggalPinjam)} • Kembali: ${_shortDate(peminjaman.tanggalKembaliRencana)}',
                    style: TextStyle(
                      color: Colors.grey.shade700,
                      fontSize: 13,
                    ),
                  ),
                ),
              ],
            ),
            if (peminjaman.denda != null &&
                peminjaman.denda!.isNotEmpty &&
                peminjaman.denda != '0') ...[
              const SizedBox(height: 6),
              Text(
                'Denda: Rp ${peminjaman.denda}',
                style: TextStyle(
                  color: Colors.orange.shade800,
                  fontSize: 13,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ],
            const SizedBox(height: 10),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                if (peminjaman.dipinjam)
                  TextButton.icon(
                    onPressed: onKembalikan,
                    icon: const Icon(Icons.assignment_return_rounded, size: 18),
                    label: const Text('Kembalikan'),
                  ),
                if (canDelete)
                  IconButton(
                    onPressed: onDelete,
                    icon: const Icon(Icons.delete_outline_rounded),
                    color: Colors.red.shade300,
                  ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Color _statusColor(String? status) {
    switch (status) {
      case 'Dipinjam':
        return AppColors.primary;
      case 'Dikembalikan':
        return Colors.green.shade600;
      case 'Terlambat':
        return Colors.orange.shade700;
      default:
        return Colors.grey.shade500;
    }
  }

  String _shortDate(String? date) {
    if (date == null || date.isEmpty) return '-';
    final d = DateTime.tryParse(date);
    if (d == null) return date;
    return '${d.day}/${d.month}/${d.year}';
  }
}
