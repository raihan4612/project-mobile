import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'core/api/api_client.dart';
import 'core/theme/app_theme.dart';
import 'data/providers/auth_provider.dart';
import 'data/providers/beasiswa_provider.dart';
import 'data/providers/buku_provider.dart';
import 'data/providers/dashboard_provider.dart';
import 'data/providers/mahasiswa_provider.dart';
import 'data/providers/peminjaman_provider.dart';
import 'data/providers/prestasi_provider.dart';
import 'data/repositories/auth_repository.dart';
import 'data/repositories/beasiswa_repository.dart';
import 'data/repositories/buku_repository.dart';
import 'data/repositories/dashboard_repository.dart';
import 'data/repositories/mahasiswa_repository.dart';
import 'data/repositories/peminjaman_repository.dart';
import 'data/repositories/prestasi_repository.dart';
import 'screens/splash_screen.dart';

void main() {
  ApiClient.instance.token = null;
  runApp(const SimakApp());
}

class SimakApp extends StatelessWidget {
  const SimakApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider(AuthRepository())),
        ChangeNotifierProxyProvider<AuthProvider, DashboardProvider>(
          create: (_) => DashboardProvider(DashboardRepository()),
          update: (_, auth, previous) =>
              previous ?? DashboardProvider(DashboardRepository()),
        ),
        ChangeNotifierProvider(create: (_) => MahasiswaProvider(MahasiswaRepository())),
        ChangeNotifierProvider(create: (_) => BukuProvider(BukuRepository())),
        ChangeNotifierProvider(
          create: (_) => PeminjamanProvider(PeminjamanRepository()),
        ),
        ChangeNotifierProvider(
          create: (_) => PrestasiProvider(PrestasiRepository()),
        ),
        ChangeNotifierProvider(
          create: (_) => BeasiswaProvider(BeasiswaRepository()),
        ),
      ],
      child: MaterialApp(
        title: 'SIMAK Mobile',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.light,
        home: const SplashScreen(),
      ),
    );
  }
}
