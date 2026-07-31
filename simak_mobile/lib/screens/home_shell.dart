import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../core/theme/app_theme.dart';
import '../data/providers/auth_provider.dart';
import '../data/models/user.dart';
import 'beasiswa/beasiswa_list_screen.dart';
import 'buku/buku_list_screen.dart';
import 'dashboard/dashboard_screen.dart';
import 'login_screen.dart';
import 'mahasiswa/mahasiswa_list_screen.dart';
import 'prestasi/prestasi_list_screen.dart';
import 'profile/profile_screen.dart';

class _MenuItem {
  final String title;
  final IconData icon;
  final Widget screen;
  final Set<String> roles;

  const _MenuItem({
    required this.title,
    required this.icon,
    required this.screen,
    required this.roles,
  });
}

class HomeShell extends StatefulWidget {
  const HomeShell({super.key});

  @override
  State<HomeShell> createState() => _HomeShellState();
}

class _HomeShellState extends State<HomeShell> {
  int _selected = 0;
  bool _collapsed = false;

  List<_MenuItem> _buildMenu(User user) {
    const all = {'admin', 'petugas', 'user'};
    const adminPetugas = {'admin', 'petugas'};
    const adminUser = {'admin', 'user'};
    final isMahasiswa = user.isMahasiswa;

    return [
      _MenuItem(
        title: 'Home',
        icon: Icons.home_rounded,
        screen: const DashboardScreen(),
        roles: all,
      ),
      _MenuItem(
        title: 'Mahasiswa',
        icon: Icons.person_outline_rounded,
        screen: const MahasiswaListScreen(),
        roles: adminPetugas,
      ),
      _MenuItem(
        title: 'Buku',
        icon: Icons.menu_book_rounded,
        screen: const BukuListScreen(),
        roles: all,
      ),
      _MenuItem(
        title: isMahasiswa ? 'Prestasi' : 'Prestasi',
        icon: Icons.emoji_events_rounded,
        screen: const PrestasiListScreen(),
        roles: all,
      ),
      _MenuItem(
        title: isMahasiswa ? 'Beasiswa' : 'Beasiswa',
        icon: Icons.workspace_premium_rounded,
        screen: const BeasiswaListScreen(),
        roles: adminUser,
      ),
      _MenuItem(
        title: 'Profile',
        icon: Icons.person_rounded,
        screen: const ProfileScreen(),
        roles: all,
      ),
    ];
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final user = auth.user;
    if (user == null) {
      return const LoginScreen();
    }

    final menu =
        _buildMenu(user).where((m) => m.roles.contains(user.role)).toList();
    if (_selected >= menu.length) _selected = 0;
    final current = menu[_selected];

    return LayoutBuilder(
      builder: (context, constraints) {
        final wide = constraints.maxWidth >= 720;

        return Scaffold(
          body: Row(
            children: [
              if (wide)
                _Sidebar(
                  menu: menu,
                  user: user,
                  selected: _selected,
                  collapsed: _collapsed,
                  onSelect: (i) => setState(() => _selected = i),
                  onLogout: _logout,
                  onToggleCollapsed: () =>
                      setState(() => _collapsed = !_collapsed),
                ),
              Expanded(child: current.screen),
            ],
          ),
          bottomNavigationBar: wide
              ? null
              : NavigationBar(
                  selectedIndex: _selected,
                  onDestinationSelected: (i) => setState(() => _selected = i),
                  labelBehavior: NavigationDestinationLabelBehavior.alwaysShow,
                  destinations: [
                    for (var i = 0; i < menu.length; i++)
                      NavigationDestination(
                        icon: Icon(menu[i].icon),
                        label: menu[i].title,
                      ),
                  ],
                ),
        );
      },
    );
  }

  Future<void> _logout() async {
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

class _Sidebar extends StatelessWidget {
  const _Sidebar({
    required this.menu,
    required this.user,
    required this.selected,
    required this.collapsed,
    required this.onSelect,
    required this.onLogout,
    required this.onToggleCollapsed,
  });

  final List<_MenuItem> menu;
  final User user;
  final int selected;
  final bool collapsed;
  final ValueChanged<int> onSelect;
  final VoidCallback onLogout;
  final VoidCallback onToggleCollapsed;

  @override
  Widget build(BuildContext context) {
    final width = collapsed ? 76.0 : 250.0;

    return Container(
      width: width,
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.08),
            blurRadius: 12,
            offset: const Offset(2, 0),
          ),
        ],
      ),
      child: Column(
        children: [
          Container(
            width: double.infinity,
            padding: EdgeInsets.all(collapsed ? 12 : 20),
            decoration: const BoxDecoration(
              gradient: AppColors.primaryGradient,
            ),
            child: collapsed
                ? Column(
                    children: [
                      const Icon(
                        Icons.school_rounded,
                        color: Colors.white,
                        size: 28,
                      ),
                      const SizedBox(height: 8),
                      IconButton(
                        tooltip: 'Perluas',
                        onPressed: onToggleCollapsed,
                        icon: const Icon(
                          Icons.menu_rounded,
                          color: Colors.white,
                        ),
                      ),
                    ],
                  )
                : Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          const Icon(
                            Icons.school_rounded,
                            color: Colors.white,
                            size: 30,
                          ),
                          const SizedBox(width: 8),
                          const Expanded(
                            child: Text(
                              'SIMAK',
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 20,
                                fontWeight: FontWeight.w800,
                                letterSpacing: 1,
                              ),
                            ),
                          ),
                          IconButton(
                            tooltip: 'Minimalkan',
                            onPressed: onToggleCollapsed,
                            icon: const Icon(
                              Icons.menu_open_rounded,
                              color: Colors.white,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 20),
                      CircleAvatar(
                        radius: 26,
                        backgroundColor:
                            Colors.white.withValues(alpha: 0.25),
                        child: Text(
                          user.nama.isNotEmpty ? user.nama[0].toUpperCase() : '?',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 22,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                      const SizedBox(height: 10),
                      Text(
                        user.nama,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.w700,
                          fontSize: 15,
                        ),
                      ),
                      Text(
                        _roleLabel(user.role),
                        style: TextStyle(
                          color: Colors.white.withValues(alpha: 0.85),
                          fontSize: 12.5,
                        ),
                      ),
                    ],
                  ),
          ),
          Expanded(
            child: ListView(
              padding: const EdgeInsets.symmetric(vertical: 10),
              children: [
                for (var i = 0; i < menu.length; i++)
                  collapsed
                      ? Padding(
                          padding: const EdgeInsets.symmetric(vertical: 2),
                          child: Tooltip(
                            message: menu[i].title,
                            child: IconButton(
                              onPressed: () => onSelect(i),
                              icon: Icon(menu[i].icon),
                              color: selected == i
                                  ? AppColors.primary
                                  : Colors.grey.shade600,
                              iconSize: 24,
                              style: IconButton.styleFrom(
                                backgroundColor: selected == i
                                    ? AppColors.primary.withValues(alpha: 0.12)
                                    : Colors.transparent,
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(12),
                                ),
                              ),
                            ),
                          ),
                        )
                      : _NavItem(
                          item: menu[i],
                          selected: selected == i,
                          onTap: () => onSelect(i),
                        ),
              ],
            ),
          ),
          const Divider(height: 1),
          if (collapsed)
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 8),
              child: Tooltip(
                message: 'Keluar',
                child: IconButton(
                  onPressed: onLogout,
                  icon: const Icon(Icons.logout_rounded),
                  color: Colors.grey.shade600,
                ),
              ),
            )
          else
            ListTile(
              leading: const Icon(Icons.logout_rounded),
              title: const Text('Keluar'),
              onTap: onLogout,
            ),
          const SizedBox(height: 8),
        ],
      ),
    );
  }

  static String _roleLabel(String role) {
    switch (role) {
      case 'admin':
        return 'Administrator';
      case 'petugas':
        return 'Petugas';
      case 'user':
        return 'Mahasiswa';
      default:
        return 'Tamu';
    }
  }
}

class _NavItem extends StatelessWidget {
  const _NavItem({
    required this.item,
    required this.selected,
    required this.onTap,
  });

  final _MenuItem item;
  final bool selected;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
      child: Material(
        color: selected
            ? AppColors.primary.withValues(alpha: 0.12)
            : Colors.transparent,
        borderRadius: BorderRadius.circular(12),
        child: ListTile(
          dense: true,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          leading: Icon(
            item.icon,
            color: selected ? AppColors.primary : Colors.grey.shade600,
          ),
          title: Text(
            item.title,
            style: TextStyle(
              color: selected ? AppColors.primary : Colors.grey.shade800,
              fontWeight: selected ? FontWeight.w700 : FontWeight.w500,
              fontSize: 14,
            ),
          ),
          onTap: onTap,
        ),
      ),
    );
  }
}
