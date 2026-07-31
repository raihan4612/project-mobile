import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/buku.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/providers/buku_provider.dart';
import '../peminjaman/peminjaman_form_screen.dart';
import '../peminjaman/peminjaman_list_screen.dart';
import 'buku_form_screen.dart';

class BukuListScreen extends StatefulWidget {
  const BukuListScreen({super.key});

  @override
  State<BukuListScreen> createState() => _BukuListScreenState();
}

class _BukuListScreenState extends State<BukuListScreen> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) context.read<BukuProvider>().load();
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
      context.read<BukuProvider>().loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<BukuProvider>();
    final isMahasiswa =
        context.watch<AuthProvider>().user?.isMahasiswa ?? false;

    return Scaffold(
      appBar: GradientAppBar(title: 'Buku'),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _searchController,
              onChanged: (v) => context.read<BukuProvider>().setSearch(v),
              decoration: InputDecoration(
                hintText: 'Cari judul / pengarang / kategori...',
                prefixIcon: const Icon(Icons.search_rounded),
                suffixIcon: _searchController.text.isEmpty
                    ? null
                    : IconButton(
                        icon: const Icon(Icons.clear_rounded),
                        onPressed: () {
                          _searchController.clear();
                          context.read<BukuProvider>().setSearch('');
                        },
                      ),
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 0, 16, 10),
            child: _PeminjamanAccess(onTap: _openPeminjaman),
          ),
          Expanded(child: _buildList(provider, isMahasiswa)),
        ],
      ),
      floatingActionButton: isMahasiswa
          ? null
          : FloatingActionButton(
              onPressed: () => _openForm(),
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              child: const Icon(Icons.add_rounded),
            ),
    );
  }

  Widget _buildList(BukuProvider provider, bool readOnly) {
    if (provider.loading) return const LoadingView();
    if (provider.error != null && provider.items.isEmpty) {
      return ErrorView(message: provider.error!, onRetry: provider.load);
    }
    if (provider.items.isEmpty) {
      return const EmptyView(title: 'Tidak ada data buku');
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
          final buku = provider.items[i];
          return _BukuCard(
            buku: buku,
            canManage: !readOnly,
            onTap: readOnly
                ? () => _showDetail(buku)
                : () => _openForm(buku),
            onPinjam: () => _pinjam(buku),
            onDelete: () => _confirmDelete(buku),
          );
        },
      ),
    );
  }

  void _showDetail(Buku buku) {
    showDialog<void>(
      context: context,
      builder: (_) => AlertDialog(
        title: Text(buku.judul),
        content: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              _detailRow('Kode', buku.kodeBuku),
              _detailRow('Pengarang', buku.pengarang ?? '-'),
              _detailRow('Penerbit', buku.penerbit ?? '-'),
              _detailRow('Tahun Terbit', buku.tahunTerbit ?? '-'),
              _detailRow('Kategori', buku.kategori ?? '-'),
              _detailRow('Stok', '${buku.jumlahTersedia}/${buku.jumlahStok}'),
              if (buku.deskripsi != null && buku.deskripsi!.isNotEmpty)
                _detailRow('Deskripsi', buku.deskripsi!),
            ],
          ),
        ),
        actions: [
          FilledButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Tutup'),
          ),
        ],
      ),
    );
  }

  Widget _detailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 110,
            child: Text(
              label,
              style: TextStyle(color: Colors.grey.shade600, fontSize: 13),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13.5),
            ),
          ),
        ],
      ),
    );
  }

  void _openForm([Buku? buku]) {
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => BukuFormScreen(buku: buku),
      ),
    );
  }

  void _pinjam(Buku buku) {
    Navigator.of(context)
        .push<bool>(
          MaterialPageRoute(
            builder: (_) => PeminjamanFormScreen(initialBukuId: buku.id),
          ),
        )
        .then((saved) {
      if (saved == true && mounted) {
        context.read<BukuProvider>().load(showLoader: false);
      }
    });
  }

  void _openPeminjaman() {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => const PeminjamanListScreen()),
    );
  }

  Future<void> _confirmDelete(Buku buku) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Hapus Buku'),
        content: Text('Hapus buku "${buku.judul}"?'),
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
        await context.read<BukuProvider>().delete(buku.id);
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Buku berhasil dihapus')),
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

class _PeminjamanAccess extends StatelessWidget {
  const _PeminjamanAccess({required this.onTap});

  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.zero,
      child: ListTile(
        onTap: onTap,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        leading: CircleAvatar(
          backgroundColor: AppColors.primary.withValues(alpha: 0.12),
          child: const Icon(Icons.swap_horiz_rounded, color: AppColors.primary),
        ),
        title: const Text(
          'Peminjaman Saya',
          style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14.5),
        ),
        subtitle: Text(
          'Lihat riwayat peminjaman',
          style: TextStyle(fontSize: 12, color: Colors.grey.shade600),
        ),
        trailing: const Icon(Icons.chevron_right_rounded),
      ),
    );
  }
}

class _BukuCard extends StatelessWidget {
  const _BukuCard({
    required this.buku,
    required this.canManage,
    required this.onTap,
    required this.onPinjam,
    required this.onDelete,
  });

  final Buku buku;
  final bool canManage;
  final VoidCallback onTap;
  final VoidCallback onPinjam;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: Column(
        children: [
          ListTile(
            onTap: onTap,
            contentPadding:
                const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
            leading: Container(
              width: 48,
              height: 58,
              decoration: BoxDecoration(
                gradient: AppColors.softGradient,
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Icon(
                Icons.menu_book_rounded,
                color: Colors.white,
                size: 26,
              ),
            ),
            title: Text(
              buku.judul,
              style: const TextStyle(fontWeight: FontWeight.w600),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            subtitle: Text(
              '${buku.pengarang ?? '-'} • ${buku.kategori ?? '-'}',
              style: TextStyle(color: Colors.grey.shade600),
            ),
            isThreeLine: false,
            trailing: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(
                  'Stok: ${buku.jumlahTersedia}/${buku.jumlahStok}',
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    color: AppColors.primary,
                  ),
                ),
                if (canManage) ...[
                  const SizedBox(height: 4),
                  PopupMenuButton<String>(
                    onSelected: (v) {
                      if (v == 'edit') onTap();
                      if (v == 'delete') onDelete();
                    },
                    itemBuilder: (_) => [
                      const PopupMenuItem(value: 'edit', child: Text('Edit')),
                      const PopupMenuItem(value: 'delete', child: Text('Hapus')),
                    ],
                    icon: const Icon(Icons.more_vert_rounded),
                  ),
                ],
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(14, 0, 14, 10),
            child: Row(
              children: [
                const Spacer(),
                if (buku.tersedia)
                  SizedBox(
                    height: 34,
                    child: FilledButton.tonalIcon(
                      onPressed: onPinjam,
                      style: FilledButton.styleFrom(
                        backgroundColor:
                            AppColors.primary.withValues(alpha: 0.12),
                        foregroundColor: AppColors.primary,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                      ),
                      icon: const Icon(Icons.swap_horiz_rounded, size: 16),
                      label: const Text(
                        'Pinjam',
                        style: TextStyle(fontWeight: FontWeight.w700),
                      ),
                    ),
                  )
                else
                  Text(
                    'Stok tidak tersedia',
                    style:
                        TextStyle(color: Colors.grey.shade500, fontSize: 12.5),
                  ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
